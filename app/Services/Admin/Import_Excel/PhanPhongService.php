<?php

namespace App\Services\Admin\Import_Excel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class PhanPhongService
{   

    protected int $capacity = 30;

    public function createRooms(int $lichThiId,string $phongThiString,string $cathi): Collection 
    {

        $tenPhongThi = array_unique(
            array_filter(
                array_map('trim', explode(',', $phongThiString))
            )
        );

        if (empty($tenPhongThi)) {
            throw new \Exception("Chưa cung cấp phòng thi.");
        }

     
        $rooms = DB::table('phong_thi')
            ->whereIn('ten_phong', $tenPhongThi)
            ->get();

        if ($rooms->count() !== count($tenPhongThi)) {
            throw new \Exception("Có phòng thi không tồn tại.");
        }

        $lichThiPhongIds = collect();

        foreach ($rooms as $room) {

            $id = DB::table('lich_thi_phong_thi_ca_thi_ngay_thi')
                ->insertGetId([
                    'lich_thi_id'  => $lichThiId,
                    'phong_thi_id' => $room->id,
                    'ca_thi_ngay_thi_id' =>$cathi,
                ]);

            $lichThiPhongIds->put($room->ten_phong,$id);
        }

        return $lichThiPhongIds;
    }

    protected function layDanhSachLopTheoMon(int $monHocId): Collection
    {
        $danhSach = DB::table('dang_ky_mon_hoc')
            ->join('sinh_vien', 'dang_ky_mon_hoc.sinh_vien_id', '=', 'sinh_vien.id')
            ->where('dang_ky_mon_hoc.mon_hoc_id', $monHocId)
            ->select(
                'sinh_vien.lop_id',
                DB::raw('COUNT(sinh_vien.id) as so_luong_sinhvien')
            )
            ->groupBy('sinh_vien.lop_id')       
            ->orderBy('sinh_vien.lop_id')
            ->get();

        if ($danhSach->isEmpty()) {
            throw new \Exception("Không có sinh viên đăng ký môn này.");
        }

        return $danhSach;
    }

    protected function phanBoLopVaoPhong(Collection $danhSachLop,Collection $danhSachLichThiPhongId,int $monHocId): void 
    {

        $phongLoads = [];

        foreach ($danhSachLichThiPhongId as $phongId) {
            $phongLoads[$phongId] = 0;
        }

        foreach ($danhSachLop as $lop) {

            $placed = false;

            foreach ($phongLoads as $phongId => $soSinhVienHienTai) {

                if ($soSinhVienHienTai + $lop->so_luong_sinhvien <= $this->capacity) {

                    DB::table('lich_thi_phong_thi_lop_thi')->insert([
                        'lich_thi_phong_thi_ca_thi_ngay_thi_id' => $phongId,
                        'lop_id' => $lop->lop_id,
                        'so_luong_sinhvien' => $lop->so_luong_sinhvien
                    ]);

                    $this->insertSinhVienVaoDiemDanh(
                        $monHocId,
                        $lop->lop_id,
                        $phongId
                    );

                    $phongLoads[$phongId] += $lop->so_luong_sinhvien;

                    $placed = true;
                    break;
                }
            }

            if (!$placed) {
                throw new \Exception("Không đủ phòng để chứa tất cả sinh viên.");
            }
        }
    }

    public function assignStudents(int $monHocId, Collection $danhSachLichThiPhongId): void
    {
        if ($danhSachLichThiPhongId->isEmpty()) {
            throw new \Exception("Không có phòng để phân sinh viên.");
        }

        $danhSachLop = $this->layDanhSachLopTheoMon($monHocId);

        $this->phanBoLopVaoPhong($danhSachLop, $danhSachLichThiPhongId->values(), $monHocId);
    }




    protected function insertSinhVienVaoDiemDanh(
        int $monHocId,
        int $lopId,
        int $lichThiPhongId
    ): void {

        $danhSachSinhVien = DB::table('dang_ky_mon_hoc')
            ->join('sinh_vien', 'dang_ky_mon_hoc.sinh_vien_id', '=', 'sinh_vien.id')
            ->join('lop', 'sinh_vien.lop_id', '=', 'lop.id')
            ->where('dang_ky_mon_hoc.mon_hoc_id', $monHocId)
            ->where('sinh_vien.lop_id', $lopId)
            ->orderBy('lop.khoa_hoc', 'asc')
            ->orderBy('sinh_vien.ho_ten_sinhvien', 'asc')
            ->select('sinh_vien.id')
            ->get();

        $duLieuInsert = [];
        
        foreach ($danhSachSinhVien as $sv) {
            $duLieuInsert[] = [
                'sinh_vien_id' => $sv->id,
                'lich_thi_phong_thi_ca_thi_ngay_thi_id' => $lichThiPhongId,
                'co_mat' => 0,
            ];
        }
       
        DB::table('diem_danh')->insert($duLieuInsert);
    }
}
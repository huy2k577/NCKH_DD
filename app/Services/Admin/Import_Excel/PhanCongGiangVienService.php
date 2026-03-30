<?php

namespace App\Services\Admin\Import_Excel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class PhanCongGiangVienService
{
public function phancongTheoPhong(
        Collection $mapPhong,
        string $chuoiPhanCongTheoPhong,
        int $caThiNgayThiId
    ): void
{
    
        if (empty($chuoiPhanCongTheoPhong)) {
            throw new \Exception("Chưa cung cấp phân công giảng viên.");
        }

        $cacPhong = explode('|', $chuoiPhanCongTheoPhong);

        foreach ($cacPhong as $duLieuPhong) {

            [$tenPhong, $chuoiGV] = explode(':', $duLieuPhong);

            $tenPhong = trim($tenPhong);

            if (!$mapPhong->has($tenPhong)) {
                throw new \Exception("Phòng $tenPhong không tồn tại trong lịch thi.");
            }

            $lichThiPhongId = $mapPhong->get($tenPhong);

            $maGVs = array_filter(
                array_map('trim', explode(',', $chuoiGV))
            );

            $giangViens = DB::table('giang_vien')
                ->whereIn('ma_giangvien', $maGVs)
                ->get();

            if ($giangViens->count() !== count($maGVs)) {
                throw new \Exception("Có giảng viên không tồn tại.");
            }

            $insertGVCTNT_id=[];
            
             foreach ($giangViens as $gv)
                {   
                        $gvctntId=DB::table('giang_vien_ca_thi_ngay_thi')
                            ->insertGetId([
                                'giang_vien_id'=> $gv->id,
                                'ca_thi_ngay_thi_id'=> $caThiNgayThiId,
                            ]);
                        $insertGVCTNT_id []=$gvctntId;
                }
        


            $insertPcct = [];

            foreach ($insertGVCTNT_id as $id) {
                            $insertPcct[] = [
                    'lich_thi_phong_thi_ca_thi_ngay_thi_id' => $lichThiPhongId,
                    'giang_vien_ca_thi_ngay_thi_id' => $id,
                    
                ];
            }

            DB::table('phan_cong_coi_thi')->insert($insertPcct);
        }
    }
}
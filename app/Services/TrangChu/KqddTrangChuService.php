<?php

namespace App\Services\TrangChu;

use Illuminate\Support\Facades\DB;

class KqddTrangChuService
{
    public function diem_danh_ket_qua($id)
    {
        $TongSV = DB::table('diem_danh')
                    ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id',$id)
                    ->count();

        $SLSV_comat = DB::table('diem_danh')
                    ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id',$id)
                    ->where('co_mat',1)
                    ->count();;

        $SLSV_khongcomat = $TongSV - $SLSV_comat;

        return response()->json([
            'TongSoluong_SinhVien' => $TongSV,
            'SoLuong_SinhVien_CoMat' => $SLSV_comat,
            'SoLuong_SinhVien_KhongCoMat' => $SLSV_khongcomat
        ]);
    }
}
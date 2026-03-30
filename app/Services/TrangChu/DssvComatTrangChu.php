<?php
namespace App\Services\TrangChu;
use Illuminate\Support\Facades\DB;
class DssvComatTrangChu
{
    public function dssv_comat($id)
    {
        $dssv_comat=DB::table('diem_danh as dd')
                ->join('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt','ltptctnt.id','=','dd.lich_thi_phong_thi_ca_thi_ngay_thi_id')
                ->join('sinh_vien as sv','sv.id','=','dd.sinh_vien_id')
                ->join('lop as l','sv.lop_id','=','l.id')
                ->where('dd.lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                ->where('dd.co_mat',1)
                ->select(
                    'sv.ma_sinhvien',
                    'sv.ho_ten_sinhvien',
                    'l.ten_lop',
                    'sv.anh_khuon_mat',
                    'sv.ngay_sinh',
                    'sv.gioi_tinh',
                    'sv.email',
                    'sv.so_dien_thoai',
                    'sv.tinh_trang',
                    'dd.co_mat',
                    'l.khoa_hoc',
                    )
                ->orderBy('l.khoa_hoc','asc')
                ->orderBy('l.ten_lop','asc')
                ->get();

        $id_ltptctnt = $id;
        return  view('trangchu.dssv_comat',compact('dssv_comat','id_ltptctnt'));
    }
}
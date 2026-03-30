<?php

namespace App\Services\Admin;
use Illuminate\Support\Facades\DB;

class EditAdminService
{
    public function edit(string $id)
    {   
            $ltptctnt = DB::table('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt')
                ->join('lich_thi as lt','lt.id','=','ltptctnt.lich_thi_id')
                ->join('mon_hoc as mh','mh.id','=','lt.mon_hoc_id')
                ->join('phong_thi as pt','pt.id','=','ltptctnt.phong_thi_id')
                ->join('ca_thi_ngay_thi as ctnt','ctnt.id','=','ltptctnt.ca_thi_ngay_thi_id')
                ->join('ky_thi as kt','kt.id','=','lt.ky_thi_id')
                ->join('ca_thi as ct','ct.id','=','ctnt.ca_thi_id')
                ->where('ltptctnt.id', $id)
                ->selectRaw('
                    ltptctnt.id,
                    lt.id as lt_id,
                    mh.ma_monhoc,
                    mh.ten_monhoc,
                    pt.ten_phong,
                    ct.gio_bat_dau,
                    ctnt.ngay_thi,
                    kt.ten_ky,
                    lt.password,
                    ct.ten_ca

                ')  
                ->first();       
             
            $ltptctnt-> sinhViens = DB::table('diem_danh as dd')
                ->join('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt','ltptctnt.id','=','dd.lich_thi_phong_thi_ca_thi_ngay_thi_id')
                ->join('sinh_vien as sv','sv.id','=','dd.sinh_vien_id')
                ->join('lop as l','sv.lop_id','=','l.id')
                ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $ltptctnt->id)
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
                    )
                ->get();
    
            $ltptctnt->so_gv = DB::table('phan_cong_coi_thi as pcct')
                ->join('giang_vien_ca_thi_ngay_thi as gvctnt', 'pcct.giang_vien_ca_thi_ngay_thi_id', '=', 'gvctnt.id')
                ->join('giang_vien as gv','gv.id','=','gvctnt.giang_vien_id')
                ->join('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt','ltptctnt.id','=','pcct.lich_thi_phong_thi_ca_thi_ngay_thi_id')
                ->where('ltptctnt.id', $ltptctnt->id)
                ->pluck('gv.ma_giangvien');
                        
                
        return view('admin.lichthi.edit', compact(
              'ltptctnt'
            ));   
        
    }
}
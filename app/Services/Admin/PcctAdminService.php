<?php

namespace App\Services\Admin;
use Illuminate\Support\Facades\DB;
class PcctAdminService
{
        public function pcct()
    {   
         $lichThis = DB::table('lich_thi')
                ->join('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt', 'ltptctnt.lich_thi_id', '=', 'lich_thi.id')
                ->join('phong_thi as pt','ltptctnt.phong_thi_id','=','pt.id')
                ->join('lich_thi_phong_thi_lop_thi as ltptlt', 'ltptlt.lich_thi_phong_thi_ca_thi_ngay_thi_id', '=', 'ltptctnt.id')
                ->join('lop as l', 'ltptlt.lop_id', '=', 'l.id')
                ->join('ca_thi_ngay_thi as ctnt', 'ltptctnt.ca_thi_ngay_thi_id', '=', 'ctnt.id')
                ->join('ca_thi', 'ctnt.ca_thi_id', '=', 'ca_thi.id')
                ->join('mon_hoc as mh', 'lich_thi.mon_hoc_id', '=', 'mh.id')
                ->join('ky_thi as kt','kt.id','=','lich_thi.ky_thi_id')
                ->selectRaw('
                    lich_thi.id,
                    mh.ma_monhoc,
                    mh.ten_monhoc,
                    mh.id as mon_hoc_id,
                    ctnt.ngay_thi,
                    ca_thi.gio_bat_dau,
                    ca_thi.gio_ket_thuc,
                    kt.ten_ky,
                    ltptctnt.id as ltptctnt_id,
                    pt.ten_phong,
                    l.ten_lop,
                    l.id as l_id,
                    so_luong_sinhvien

                ')
                ->orderBy('ctnt.ngay_thi', 'asc')
                ->orderBy('ca_thi.gio_bat_dau', 'asc')
                ->orderBy('mh.ten_monhoc', 'desc')
                ->orderBy('mh.ten_monhoc', 'desc')
                ->orderBy('l.ten_lop', 'asc')
                ->paginate(10);
                
            foreach($lichThis as $lt)
                {
                   $lt->so_gv = DB::table('phan_cong_coi_thi as pcct')
                        ->join('giang_vien_ca_thi_ngay_thi as gvctnt', 'pcct.giang_vien_ca_thi_ngay_thi_id', '=', 'gvctnt.id')
                        ->join('giang_vien as gv', 'gvctnt.giang_vien_id', '=', 'gv.id')
                        ->where('pcct.lich_thi_phong_thi_ca_thi_ngay_thi_id', $lt->ltptctnt_id)
                        ->pluck('gv.ho_ten_giangvien'); 
                }

            return view('admin.lichthi.pcct', compact(
                'lichThis',
            ));
    }
}
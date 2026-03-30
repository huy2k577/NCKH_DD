<?php

namespace App\Services\Admin;
use Illuminate\Support\Facades\DB;
class DestroyAdminService
{ 
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {   
            
           
            $lt = DB::table('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt')
                ->join('lich_thi as lt','lt.id','=','ltptctnt.lich_thi_id')
                ->where('ltptctnt.id',$id)
                ->select('lt.id')
                ->first();

            $gvct_Ids = DB::table('phan_cong_coi_thi')
                ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                ->pluck('giang_vien_ca_thi_ngay_thi_id')
                ->toArray();


            DB::table('phan_cong_coi_thi')
                ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                ->delete();
    
            if (!empty($gvct_Ids)) {
                DB::table('giang_vien_ca_thi_ngay_thi')
                    ->whereIn('id', $gvct_Ids)
                    ->delete();
            }
            
            DB::table('diem_danh')
                ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                ->delete();

            DB::table('lich_thi_phong_thi_lop_thi')
                ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                ->delete();

                
            DB::table('lich_thi_phong_thi_ca_thi_ngay_thi')
                ->where('id',$id)
                ->delete();

            $conPhong = DB::table('lich_thi_phong_thi_ca_thi_ngay_thi')
                ->where('lich_thi_id',$lt->id)
                ->exists();

            if(!$conPhong){
                DB::table('lich_thi')
                    ->where('id',$lt->id)
                    ->delete();
            }

            DB::commit();

            return redirect()->route('admin.lichthi.index')
                ->with('success_delete', 'Xóa lịch thi thành công.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error_delete', 'Không thể xóa lịch thi: ' . $e->getMessage());
        }
    }
}
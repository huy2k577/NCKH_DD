<?php

namespace App\Services\TrangChu;

use App\Http\Requests\TrangChuRequests;
use Illuminate\Support\Facades\DB;

                  
class LoginPostTrangChuService 
{  
     public function login_post_trangchu(TrangChuRequests $request)
    {   
         
        $id_phong=DB::table('phong_thi')
            ->where('ten_phong',$request->ten_phong)
            ->value('id');

        $id_ca=DB::table('ca_thi')
            ->where('ten_ca',$request->ten_ca)
            ->value('id');

        $id_ctnt=DB::table('ca_thi_ngay_thi')
            ->where('ca_thi_id',$id_ca)
            ->where('ngay_thi',now()->toDateString())
            ->value('id');

        $id_ltptctnt=DB::table('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt')
            ->where('phong_thi_id',$id_phong)
            ->where('ca_thi_ngay_thi_id',$id_ctnt)
            ->value('id');

        if($id_ltptctnt==null)
            {
                return back()->withErrors(['No_lichthi' => 'Không có lịch thi này hôm nay']);
            }

        return redirect()->route('show.trangchu', $id_ltptctnt);
    }
}
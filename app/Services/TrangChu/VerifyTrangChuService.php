<?php

namespace App\Services\TrangChu;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VerifyTrangChuService
{
     public function verify(Request $request,$id)
    {
            $lichThi = DB::table('lich_thi_phong_thi_ca_thi_ngay_thi as ltpt')
                ->join('lich_thi as lt', 'lt.id', '=', 'ltpt.lich_thi_id')
                ->where('ltpt.id', $id) 
                ->select('lt.password')
                ->first();

            if (!Hash::check($request->password, $lichThi->password)) {
                return back()->withErrors(['error' => 'Sai mật khẩu']);
            }

            session(['lichthi_verified_' . $id => true]); 

            return redirect()->route('show.trangchu', $id);
    }
}
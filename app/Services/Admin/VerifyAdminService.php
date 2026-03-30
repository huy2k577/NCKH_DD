<?php

namespace App\Services\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VerifyAdminService
{  
    public function verify_admin(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'password' => 'required'
            ]);
           
            if (Auth::guard('web')->attempt([
                'name' => $request->name,
                'password' => $request->password
            ])) 
            {   
                $user = Auth::user();
               
                if (!$user->hasPermission('admin_lichthi')) {
                    Auth::logout();
                    return back()->withErrors(['error_dangnhapadmin' => 'Bạn không phải admin']);
                }
                //$request->session()->put('admin_logged_in', true);
                $request->session()->regenerate();      //xóa session id cũ tạo session mới
                    return redirect()->route('admin.lichthi.index');
            }
            else
                {
                    return redirect()->back()
                        ->withErrors(['error' => 'Tài khoản không đúng']);
                }
            
        }

}   
<?php

namespace App\Services\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LogoutAdminService
{
    public function logout(Request $request)
        {
            Auth::logout(); //Tạo lại CSRF token mới.    

            $request->session()->invalidate(); //Xóa toàn bộ dữ liệu session hiện tại

            $request->session()->regenerateToken();

            return redirect()->route('login.get.trangchu');
        }
}
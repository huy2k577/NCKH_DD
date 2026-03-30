<?php

namespace App\Services\TrangChu;

use Illuminate\Support\Facades\DB;

class loginTrangChuService
{
     public function login_get_trangchu()
    {   
        
        return view('trangchu.trangchu');
    }
}
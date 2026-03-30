<?php
namespace App\Services\Admin\Import_Excel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LichThiCreator
{
    public function create(array $data, $monHocId,$ky_thi)
    {
        return DB::table('lich_thi')->insertGetId([
            'mon_hoc_id'  => $monHocId,
            'ky_thi_id' => $ky_thi,
            'password'    => Hash::make($data['password']),
            
        ]);
    }
}
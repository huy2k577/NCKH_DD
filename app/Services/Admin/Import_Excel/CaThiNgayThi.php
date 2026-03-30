<?php

namespace App\Services\Admin\Import_Excel;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CaThiNgayThi
{
     public function cathingaythi(int $ca,  $ngaythi):int
     {
           // Nếu là số (Excel serial)
        if (is_numeric($ngaythi)) {
            $date = Carbon::instance(
                ExcelDate::excelToDateTimeObject($ngaythi)
            )->format('Y-m-d');
        } 
        else 
            {
            $date = Carbon::parse($ngaythi)->format('Y-m-d');
        }

        // 1️⃣ Tìm trước
        $record = DB::table('ca_thi_ngay_thi')
            ->where('ca_thi_id', $ca)
            ->where('ngay_thi', $date)
            ->first();

        if ($record) {
            return $record->id;
        }

        // 2️⃣ Nếu chưa có thì insert
        return DB::table('ca_thi_ngay_thi')->insertGetId([
            'ca_thi_id' => $ca,
            'ngay_thi'  => $date,
         
        ]);
    }
}

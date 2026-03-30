<?php

namespace App\Services\Admin\Import_Excel;


use Illuminate\Support\Facades\DB;

class LichThiValidator
{
    public function validate(array $data): void
    {
        
        if (empty($data['ma_monhoc'])) {
            throw new \Exception("Chưa cung cấp phòng thi môn học.");
        }
      
        if (!DB::table('mon_hoc')->where('ma_monhoc', $data['ma_monhoc'])->exists()) {
            throw new \Exception("Mã môn học không tồn tại.");
        }

        if (empty($data['ca_thi'])) {
            throw new \Exception("Chưa cung cấp  ca thi.");
        }

        if (!DB::table('ca_thi')->where('ten_ca', $data['ca_thi'])->exists()) {
            throw new \Exception("Ca thi không tồn tại.");
        }

        if (empty($data['phong_thi'])) {
            throw new \Exception("Chưa cung cấp phòng thi.");
        }

        if (!DB::table('phong_thi')->where('ten_phong', $data['phong_thi'])->exists()) {
            throw new \Exception("Phòng thi không tồn tại.");
        }

        if (empty($data['ma_giangvien'])) {
            throw new \Exception("Chưa cung cấp phân công coi thi.");
        }

       $parts = explode(':', $data['ma_giangvien']);

        if (count($parts) > 4) {
            throw new \Exception("Tối đa có 4 cán bộ coi thi.");
        }
         
        if (empty($data['password'])) {
            throw new \Exception("Chưa cung cấp password lich thi .");
        }
      
        if (strlen($data['password'])<8) {
            throw new \Exception("Password cần 8 ký tự.");
        }

        $dsGiangVien = explode(',', $parts[1]);

        foreach ($dsGiangVien as $maGV) {
            $maGV = trim($maGV);

            if (!DB::table('giang_vien')->where('ma_giangvien', $maGV)->exists()) {
                throw new \Exception("Giảng viên $maGV không tồn tại.");
            }
        }
    }
    
}
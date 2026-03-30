<?php

namespace App\Services\Admin\Import_Excel;

use Illuminate\Database\QueryException;

class DatabaseExceptionHandler
{
    public static function handle(QueryException $e): string
    {
        $message = '';

        if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {

            $errorMessage = $e->getMessage();

            if (str_contains($errorMessage, 'uq_phong_ca_thi')) {
                $message = 'Phòng này đã có lịch thi ở ca này và ngày này.';
            }
            elseif (str_contains($errorMessage, 'uq_giang_vien_ca_thi_ngay_thi')) {
                $message = 'Giảng viên đã được phân công coi thi ở ca này trong ngày này.';
            }
            else {
                $message = 'Dữ liệu bị trùng (vi phạm ràng buộc unique).';
            }

        } else {
            $message = 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage();
        }

        return $message;
    }
}
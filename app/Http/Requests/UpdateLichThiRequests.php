<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLichThiRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
       
            'ma_monhoc' => 'required|exists:mon_hoc,ma_monhoc',
            'ten_phong' => 'required|exists:phong_thi,ten_phong',
            'ten_ca'     => 'required|exists:ca_thi,ten_ca',
            'ngay_thi'  => 'required|date|after_or_equal:today',
            'ma_giangvien' => 'required|array|min:1',
            'ma_giangvien.*' => 'exists:giang_vien,ma_giangvien|distinct',
            'password' => 'nullable|min:8',
            
        ];
    }

    public function messages()
    {
        return [
            'ma_monhoc.required' => 'Vui lòng nhập mã môn học',
            'ma_monhoc.exists' => 'Mã môn học không tồn tại',
            'ten_ca.required' => 'Vui lòng nhập ca thi',
            'ten_ca.exists' => 'Ca thi không tồn tại',
            'ten_phong.required' => 'Vui lòng nhập tên phòng thi',
            'ten_phong.exists' => 'Tên phòng không tồn tại',
            'ngay_thi.required' => 'Vui lòng nhập ngày thi',
            'ngay_thi.date' => 'Ngày thi không hợp lệ',
            'ngay_thi.after_or_equal' => 'Ngày thi phải từ hôm nay trở đi',
            'ma_giangvien.required' => 'Vui lòng nhập giảng viên coi thi (tối thiểu 1 giảng viên)',
            'ma_giangvien.*.exists' => 'Mã giảng viên không tồn tại',
            'ma_giangvien.*.distinct' => 'Giảng viên không được trùng nhau',
            'ma_giangvien.min' => 'Phải có ít nhất 1 giảng viên coi thi',
            'password.min' => 'Password ít nhất 8 ký tự',

        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ma_giangvien' => array_values(array_filter($this->ma_giangvien ?? [])) // array_filter loại bỏ  null,"",0,flase.
                                                                                    //array_values(...) Reset lại index cho gọn.
                                                                                    // ví dụ [0 => "GV01", 2 => "GV02"] => [0 => "GV01", 1 => "GV02"]
        ]);
    }
    
}

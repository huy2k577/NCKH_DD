<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrangChuRequests extends FormRequest
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
            'ten_phong' => 'required|exists:phong_thi,ten_phong',
            'ten_ca'     => 'required|exists:ca_thi,ten_ca',
        ];
    }

    public function messages()
    {
        return [
            'ten_ca.required' => 'Vui lòng nhập ca thi',
            'ten_ca.exists' => 'Ca thi không tồn tại',
            'ten_phong.required' => 'Vui lòng nhập tên phòng thi',
            'ten_phong.exists' => 'Tên phòng không tồn tại',
        ];
    }
}

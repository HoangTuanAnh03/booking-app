<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FieldOpeningHoursRequest extends FormRequest
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
            'field_id' => 'required|uuid|exists:fields,field_id',
            'opening_hours' => 'required|array|min:1',
            'opening_hours.*.day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'opening_hours.*.opening_time' => 'required|date_format:H:i',
            'opening_hours.*.closing_time' => 'required|date_format:H:i|after:opening_hours.*.opening_time',
        ];
    }

    public function messages(): array
    {
        return [
            'field_id.required' => 'Thiếu mã sân.',
            'field_id.exists' => 'Mã sân không tồn tại',
            'opening_hours.required' => 'Vui lòng cung cấp giờ mở cửa.',
            'opening_hours.*.day_of_week.in' => 'Ngày không hợp lệ.',
            'opening_hours.*.closing_time.after' => 'Giờ đóng phải sau giờ mở.',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 400,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], 422));
    }
}

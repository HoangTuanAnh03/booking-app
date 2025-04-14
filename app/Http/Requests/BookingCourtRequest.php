<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingCourtRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có được phép gửi request không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các rules dùng để validate request.
     */
    public function rules(): array
    {
        return [
            'booking_id' => 'required|uuid',
            'court_id' => 'required|uuid',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'success' => 400,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}

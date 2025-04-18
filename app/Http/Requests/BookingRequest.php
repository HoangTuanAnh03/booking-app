<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingRequest extends FormRequest
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
            'field_id' => ['required', 'string', 'exists:fields,field_id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'booking_date' => ['required', 'after_or_equal:today'],
            'court' => ['required', 'array'],
            'court.*' => ['array'],
            'court.*.*.start_time' => ['required', 'date_format:H:i', 'before:court.*.*.end_time'],
            'court.*.*.end_time' => ['required', 'date_format:H:i', 'after:court.*.*.start_time'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'field_id.exists' => 'The selected field does not exist.',
            'booking_date.after_or_equal' => 'The booking date must be today or later.',
            'court.*.*.start_time.date_format' => 'The start time must be in HH:MM format (e.g., 10:00).',
            'court.*.*.end_time.date_format' => 'The end time must be in HH:MM format (e.g., 11:00).',
            'court.*.*.start_time.before' => 'The start time must be before the end time.',
            'court.*.*.end_time.after' => 'The end time must be after the start time.',
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

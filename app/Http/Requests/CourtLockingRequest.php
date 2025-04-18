<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourtLockingRequest extends FormRequest
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
            'date' => ['required', 'after_or_equal:today'],
            'court' => ['required', 'array'],
            'court.*' => ['array'],
            'court.*.*.start_time' => ['required', 'date_format:H:i', 'before:court.*.*.end_time'],
            'court.*.*.end_time' => ['required', 'date_format:H:i', 'after:court.*.*.start_time'],
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

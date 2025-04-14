<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourtRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'field_id' => 'required|string',
            'court_name' => 'required|string',
            'is_active' => 'required|boolean',
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

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FieldRequest extends FormRequest
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
            //
            'venue_id' => 'required|string|exists:venues,venue_id',
            'sport_type_id' => 'required|numeric|exists:sport_types,sport_type_id',
            'field_name' => 'required|string',
            'default_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'status' => 400,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}

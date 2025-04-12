<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FieldPriceRequest extends FormRequest
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
            'field_prices' => 'required|array|min:1',
            'field_prices.*.day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'field_prices.*.start_time' => 'required|date_format:H:i',
            'field_prices.*.end_time' => 'required|date_format:H:i|after:field_prices.*.start_time',
            'field_prices.*.price' => 'required|numeric|min:0',
            'field_prices.*.min_rental' => 'required|integer|min:1',
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

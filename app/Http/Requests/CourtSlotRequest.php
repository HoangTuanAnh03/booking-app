<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourtSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'court_id' => 'required|uuid|exists:court,court_id',
            'booking_court_id' => 'required|uuid|exists:bookingCourts,booking_court_id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_looked' => 'required|boolean',
            'locked_by_owner' => 'required|boolean',
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenuePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}

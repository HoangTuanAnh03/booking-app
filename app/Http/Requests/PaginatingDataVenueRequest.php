<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatingDataVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // nếu cần auth thì bạn có thể xử lý thêm
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sortBy' => ['nullable', 'string'],
            'sortDirection' => ['nullable', 'in:asc,desc'],
        ];
    }

}

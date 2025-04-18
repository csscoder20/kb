<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['required', 'string', 'min:3']
        ];
    }

    public function messages(): array
    {
        return [
            'keyword.required' => 'Search keyword is required',
            'keyword.min' => 'Search keyword must be at least 3 characters'
        ];
    }
}

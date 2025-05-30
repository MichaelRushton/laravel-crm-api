<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email_address' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}

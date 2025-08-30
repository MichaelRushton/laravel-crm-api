<?php

namespace App\Http\Requests\V1\ResetPassword;

use App\Services\PasswordService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', Password::min(PasswordService::MIN_LENGTH)],
            'password_confirmation' => ['confirmed:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Please enter your new password.',
            'password.min' => 'Your password must be at least :min characters.',
            'password_confirmation.confirmed' => 'The password confirmation does not match.',
        ];
    }
}

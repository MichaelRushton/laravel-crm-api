<?php

namespace App\Http\Requests\V1;

use App\Services\V1\PasswordService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('update', $this->route('user'));

        return true;

    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string'],
            'last_name' => ['sometimes', 'required', 'string'],
            'email_address' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($this->route('user'))],
            'password' => ['sometimes', 'required', 'string', Password::min(PasswordService::MIN_LENGTH)],
        ];
    }
}

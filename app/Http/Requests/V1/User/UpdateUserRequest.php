<?php

namespace App\Http\Requests\V1\User;

use App\Enums\UserRole;
use App\Services\PasswordService;
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
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($this->route('user'))],
            'password' => ['sometimes', 'required', 'string', Password::min(PasswordService::MIN_LENGTH)],
            'role' => ['sometimes', 'string', Rule::enum(UserRole::class)],
        ];
    }
}

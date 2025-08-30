<?php

namespace App\Http\Requests\V1\User;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('create', User::class);

        return true;

    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', Password::min(PasswordService::MIN_LENGTH)],
            'role' => ['required', 'string', Rule::enum(UserRole::class)],
        ];
    }
}

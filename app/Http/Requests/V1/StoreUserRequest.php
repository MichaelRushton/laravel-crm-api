<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use App\Services\V1\PasswordService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
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
            'email_address' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', Password::min(PasswordService::MIN_LENGTH)],
        ];
    }
}

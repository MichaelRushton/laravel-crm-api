<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('update', $this->route('customer'));

        return true;

    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string'],
            'last_name' => ['sometimes', 'required', 'string'],
            'email_address' => ['sometimes', 'required', 'email', Rule::unique('customers')->ignore($this->route('customer'))],
        ];
    }
}

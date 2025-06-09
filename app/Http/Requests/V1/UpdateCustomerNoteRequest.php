<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCustomerNoteRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('update', $this->route('note'));

        return true;

    }

    public function rules(): array
    {
        return [
            'note' => ['required', 'string'],
        ];
    }
}

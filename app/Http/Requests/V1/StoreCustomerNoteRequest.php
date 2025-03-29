<?php

namespace App\Http\Requests\V1;

use App\Models\CustomerNote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCustomerNoteRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('create', CustomerNote::class);

        return true;

    }

    public function rules(): array
    {
        return [
            'note' => ['required', 'string'],
        ];
    }
}

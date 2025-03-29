<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateEnquiryRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('update', $this->route('enquiry'));

        return true;

    }

    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'exists:products,id'],
        ];
    }
}

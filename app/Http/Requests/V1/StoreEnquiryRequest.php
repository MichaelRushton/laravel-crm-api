<?php

namespace App\Http\Requests\V1;

use App\Models\Enquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('create', Enquiry::class);

        return true;

    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
        ];
    }
}

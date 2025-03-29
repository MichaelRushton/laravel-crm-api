<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('update', $this->route('product'));

        return true;

    }

    public function rules(): array
    {
        return [
            'product_category_id' => ['sometimes', 'exists:product_categories,id'],
            'name' => ['sometimes', 'required', 'string', Rule::unique('products')->ignore($this->route('product'))],
            'description' => ['sometimes', 'required', 'string'],
        ];
    }
}

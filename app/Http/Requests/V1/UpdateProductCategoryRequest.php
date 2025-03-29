<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProductCategoryRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('update', $this->route('product_category'));

        return true;

    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', Rule::unique('product_categories')->ignore($this->route('product_category'))],
        ];
    }
}

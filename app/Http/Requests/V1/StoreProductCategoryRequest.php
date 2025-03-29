<?php

namespace App\Http\Requests\V1;

use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProductCategoryRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('create', ProductCategory::class);

        return true;

    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:product_categories'],
        ];
    }
}

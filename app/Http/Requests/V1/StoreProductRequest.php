<?php

namespace App\Http\Requests\V1;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProductRequest extends FormRequest
{
    public function authorize(): true
    {

        Gate::authorize('create', Product::class);

        return true;

    }

    public function rules(): array
    {
        return [
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'unique:products'],
            'description' => ['required', 'string'],
        ];
    }
}

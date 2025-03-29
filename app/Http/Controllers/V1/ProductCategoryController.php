<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProductCategoryRequest;
use App\Http\Requests\V1\UpdateProductCategoryRequest;
use App\Http\Resources\V1\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Policies\V1\ProductCategoryPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        Gate::policy(ProductCategory::class, ProductCategoryPolicy::class);
    }

    public function index(Request $request): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', ProductCategory::class);

        return ProductCategoryResource::collection(
            ProductCategory::filter((array) $request->input('filter', []))
                ->orderBy('name')
                ->paginate((int) $request->input('per_page', 15))
        );

    }

    public function store(StoreProductCategoryRequest $request): ProductCategoryResource
    {

        $product_category = ProductCategory::create($request->validated());

        return new ProductCategoryResource($product_category);

    }

    public function show(ProductCategory $product_category): ProductCategoryResource
    {

        Gate::authorize('view', $product_category);

        return new ProductCategoryResource($product_category);

    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $product_category): ProductCategoryResource
    {

        $product_category->update($request->validated());

        return new ProductCategoryResource($product_category);

    }

    public function destroy(ProductCategory $product_category): void
    {

        Gate::authorize('delete', $product_category);

        $product_category->delete();

        abort(204);

    }
}

<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProductRequest;
use App\Http\Requests\V1\UpdateProductRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Policies\V1\ProductPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct()
    {
        Gate::policy(Product::class, ProductPolicy::class);
    }

    public function index(Request $request): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', Product::class);

        return ProductResource::collection(
            Product::filter((array) $request->input('filter', []))
                ->orderBy('name')
                ->with('category')
                ->paginate((int) $request->input('per_page', 15))
        );

    }

    public function store(StoreProductRequest $request): ProductResource
    {

        $product = Product::create($request->validated());

        return new ProductResource($product);

    }

    public function show(Product $product): ProductResource
    {

        Gate::authorize('view', $product);

        return new ProductResource($product);

    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {

        $product->update($request->validated());

        return new ProductResource($product);

    }

    public function destroy(Product $product): void
    {

        Gate::authorize('delete', $product);

        $product->delete();

        abort(204);

    }
}

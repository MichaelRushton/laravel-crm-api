<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Customer\StoreCustomerRequest;
use App\Http\Requests\V1\Customer\UpdateCustomerRequest;
use App\Http\Resources\V1\Customer\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', Customer::class);

        return CustomerResource::collection(
            Customer::filter((array) $request->filter)
                ->sort((array) $request->sort)
                ->orderBy('id')
                ->paginate((int) ($request->per_page ?: 100))
        );

    }

    public function store(StoreCustomerRequest $request): CustomerResource
    {

        $customer = Customer::create($request->validated());

        return new CustomerResource($customer);

    }

    public function show(Customer $customer): CustomerResource
    {

        Gate::authorize('view', $customer);

        return new CustomerResource($customer);

    }

    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {

        $customer->update($request->validated());

        return new CustomerResource($customer);

    }

    public function destroy(Customer $customer): Response
    {

        Gate::authorize('delete', $customer);

        $customer->delete();

        return response()->noContent();

    }
}

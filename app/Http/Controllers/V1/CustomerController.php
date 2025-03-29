<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\EnquiryResource;
use App\Models\Customer;
use App\Policies\V1\CustomerPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function __construct()
    {
        Gate::policy(Customer::class, CustomerPolicy::class);
    }

    public function index(Request $request): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', Customer::class);

        return CustomerResource::collection(
            Customer::filter((array) $request->input('filter', []))
                ->sort(((array) $request->input('sort', [])))
                ->orderBy('id')
                ->paginate((int) $request->input('per_page', 15))
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

    public function destroy(Customer $customer): void
    {

        Gate::authorize('delete', $customer);

        $customer->delete();

        abort(204);

    }

    public function enquiries(Customer $customer): AnonymousResourceCollection
    {

        Gate::authorize('enquiries', $customer);

        return EnquiryResource::collection(
            $customer->enquiries()->with(['product.category'])->get()
        );

    }
}

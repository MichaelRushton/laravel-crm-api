<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreEnquiryRequest;
use App\Http\Requests\V1\UpdateEnquiryRequest;
use App\Http\Resources\V1\EnquiryResource;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Policies\V1\EnquiryPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class EnquiryController extends Controller
{
    public function __construct()
    {
        Gate::policy(Enquiry::class, EnquiryPolicy::class);
    }

    public function index(Request $request): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', Enquiry::class);

        return EnquiryResource::collection(
            Enquiry::filter((array) $request->input('filter', []))
                ->orderByDesc('id')
                ->with(['customer', 'product.category'])
                ->paginate((int) $request->input('per_page', 15))
        );

    }

    public function store(StoreEnquiryRequest $request, Customer $customer): EnquiryResource
    {

        $enquiry = $customer->enquiries()->create($request->validated());

        return new EnquiryResource($enquiry);

    }

    public function show(Customer $customer, Enquiry $enquiry): EnquiryResource
    {

        if ($enquiry->customer_id !== $customer->id) {
            abort(404);
        }

        Gate::authorize('view', $enquiry);

        return new EnquiryResource($enquiry);

    }

    public function update(UpdateEnquiryRequest $request, Customer $customer, Enquiry $enquiry): EnquiryResource
    {

        if ($enquiry->customer_id !== $customer->id) {
            abort(404);
        }

        $enquiry->update($request->validated());

        return new EnquiryResource($enquiry);

    }

    public function destroy(Customer $customer, Enquiry $enquiry): void
    {

        if ($enquiry->customer_id !== $customer->id) {
            abort(404);
        }

        Gate::authorize('delete', $enquiry);

        $enquiry->delete();

        abort(204);

    }
}

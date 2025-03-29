<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerNoteRequest;
use App\Http\Requests\V1\UpdateCustomerNoteRequest;
use App\Http\Resources\V1\CustomerNoteResource;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Policies\V1\CustomerNotePolicy;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class CustomerNoteController extends Controller
{
    public function __construct()
    {
        Gate::policy(CustomerNote::class, CustomerNotePolicy::class);
    }

    public function index(Customer $customer): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', CustomerNote::class);

        return CustomerNoteResource::collection(
            $customer->notes()->with('createdBy')->get()
        );

    }

    public function store(StoreCustomerNoteRequest $request, Customer $customer): CustomerNoteResource
    {

        $note = $customer->notes()->create($request->validated());

        return new CustomerNoteResource($note);

    }

    public function show(Customer $customer, CustomerNote $note): CustomerNoteResource
    {

        if ($note->customer_id !== $customer->id) {
            abort(404);
        }

        Gate::authorize('view', $note);

        return new CustomerNoteResource($note);

    }

    public function update(UpdateCustomerNoteRequest $request, Customer $customer, CustomerNote $note): CustomerNoteResource
    {

        if ($note->customer_id !== $customer->id) {
            abort(404);
        }

        $note->update($request->validated());

        return new CustomerNoteResource($note);

    }

    public function destroy(Customer $customer, CustomerNote $note): void
    {

        if ($note->customer_id !== $customer->id) {
            abort(404);
        }

        Gate::authorize('delete', $note);

        $note->delete();

        abort(204);

    }
}

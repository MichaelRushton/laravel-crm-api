<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Policies\V1\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {
        Gate::policy(User::class, UserPolicy::class);
    }

    public function index(Request $request): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', User::class);

        return UserResource::collection(
            User::filter((array) $request->input('filter', []))
                ->sort(((array) $request->input('sort', [])))
                ->orderBy('id')
                ->paginate((int) $request->input('per_page', 15))
        );

    }

    public function store(StoreUserRequest $request): UserResource
    {

        $user = User::create($request->validated())->refresh();

        return new UserResource($user);

    }

    public function show(User $user): UserResource
    {

        Gate::authorize('view', $user);

        return new UserResource($user);

    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {

        $user->update($request->validated());

        return new UserResource($user);

    }

    public function destroy(User $user): void
    {

        Gate::authorize('delete', $user);

        $user->delete();

        abort(204);

    }
}

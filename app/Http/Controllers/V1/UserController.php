<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\StoreUserRequest;
use App\Http\Requests\V1\User\UpdateUserRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use SensitiveParameter;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {

        Gate::authorize('viewAny', User::class);

        return UserResource::collection(
            User::filter((array) $request->filter)
                ->sort((array) $request->sort)
                ->orderBy('id')
                ->paginate((int) ($request->per_page ?: 100))
        );

    }

    public function store(#[SensitiveParameter] StoreUserRequest $request): UserResource
    {

        $user = User::create($request->validated())->refresh();

        return new UserResource($user);

    }

    public function show(User $user): UserResource
    {

        Gate::authorize('view', $user);

        return new UserResource($user);

    }

    public function update(#[SensitiveParameter] UpdateUserRequest $request, User $user): UserResource
    {

        $user->update($request->validated());

        return new UserResource($user);

    }

    public function destroy(User $user): Response
    {

        Gate::authorize('delete', $user);

        $user->delete();

        return response()->noContent();

    }
}

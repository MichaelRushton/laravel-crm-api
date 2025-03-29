<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAuthRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function store(StoreAuthRequest $request): JsonResponse
    {

        $user = User::where('email_address', $request->email_address)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            abort(401);
        }

        $user->tokens()->delete();

        return response()->json([
            'data' => ['token' => $user->createToken('auth')->plainTextToken],
        ]);

    }

    public function show(): UserResource
    {
        return new UserResource(Auth::user());
    }

    public function destroy(): void
    {

        Auth::user()->tokens()->delete();

        abort(204);

    }
}

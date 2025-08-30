<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\StoreAuthRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Timebox;
use SensitiveParameter;

class AuthController extends Controller
{
    public function store(#[SensitiveParameter] StoreAuthRequest $request): JsonResponse
    {

        $token = (new Timebox)->call(function (Timebox $timebox) use ($request) {

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return null;
            }

            $user->tokens()->delete();

            $timebox->returnEarly();

            return $user->createToken('auth')->plainTextToken;

        }, 200000);

        if (! $token) {
            abort(401);
        }

        return response()->json([
            'data' => ['token' => $token],
        ]);

    }

    public function show(): UserResource
    {
        return new UserResource(Auth::user());
    }

    public function destroy(): Response
    {

        Auth::user()->tokens()->delete();

        return response()->noContent();

    }
}

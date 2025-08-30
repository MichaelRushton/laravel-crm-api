<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ResetPassword\StoreResetPasswordRequest;
use App\Http\Requests\V1\ResetPassword\UpdateResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Inertia\Response as InertiaResponse;
use SensitiveParameter;

class ResetPasswordController extends Controller
{
    public function store(StoreResetPasswordRequest $request): Response
    {

        $email = $request->only('email');

        dispatch(fn () => Password::sendResetLink($email));

        return response()->noContent();

    }

    public function edit(Request $request, string $token): InertiaResponse
    {

        if (! $request->email) {
            abort(404);
        }

        return inertia('ResetPassword/Edit', [
            'token' => $token,
            'email' => $request->email,
        ]);

    }

    public function update(#[SensitiveParameter] UpdateResetPasswordRequest $request): RedirectResponse
    {

        $status = Password::reset(
            $request->validated(),
            fn (User $user, #[SensitiveParameter] string $password) => $user->update(['password' => $password])
        );

        if ($status !== Password::PasswordReset) {
            return back()->withErrors(['password' => [__($status)]]);
        }

        return back()->withFlash([
            'success' => 'Your password has been updated.',
        ]);

    }
}

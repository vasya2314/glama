<?php

namespace App\Http\Controllers\v1\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\AuthRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function sendEmail(AuthRequest $request): JsonResponse
    {
        $request->validated();
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }

        return response()->json(['code' => Response::HTTP_OK, 'message' => __($status)]);
    }

    public function resetPassword(AuthRequest $request): JsonResponse
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET)
        {
            return response()->json(['code' => Response::HTTP_OK, 'message' => __('Password reset successfully')]);
        }

        return response()->json(['code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => __($status)]);
    }
}

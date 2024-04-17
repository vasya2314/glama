<?php

namespace App\Http\Controllers\v1\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $this->hasVerifiedEmail($request);
        if ($request->user()->markEmailAsVerified())
        {
            event(new Verified($request->user()));
        }

        return response()->json(['code' => Response::HTTP_OK, 'message' => __('Your account has been successfully verified')]);
    }

    /**
     * @throws ValidationException
     */
    public function resend(Request $request)
    {
        $this->hasVerifiedEmail($request);
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['code' => Response::HTTP_OK, 'message' => __('Verification link sent')]);
    }

    /**
     * @throws ValidationException
     */
    private function hasVerifiedEmail($request): void
    {
        if ($request->user()->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'message' => __('Your account has already verified')
            ]);
        }
    }
}

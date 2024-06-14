<?php

namespace App\Http\Controllers\v1\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\AuthRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function register(AuthRequest $request, string $userType = User::TYPE_SIMPLE, int|null $parentId = null): JsonResponse
    {
        if ($user = User::create($request->validatedData($userType, $parentId))) {
            event(new Registered($user));

            return response()->json(['code' => Response::HTTP_CREATED, 'message' => __('Account has been successfully registered, please check your email to verify your account.')], Response::HTTP_CREATED);
        }

        return response()->json(['code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => __('Your account failed to register.')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

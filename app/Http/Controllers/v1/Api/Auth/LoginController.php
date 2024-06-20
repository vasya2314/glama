<?php

namespace App\Http\Controllers\v1\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\AuthRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(AuthRequest $request, User $user = null): JsonResponse
    {
        if($user == null)
        {
            $credentials = $request->only('email', 'password');

            if(!Auth::attempt($credentials))
            {
                throw ValidationException::withMessages([
                    'message' => __('The credentials does not match.')
                ]);
            }

            $user = Auth::user();
        }

        $user = (new UserResource($user))
            ->additional(['token' => $user->createToken('access-token', [$user->role])->plainTextToken])
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Logged in succesfully.'), $user);
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user()->tokens()->delete()) return $this->wrapResponse(Response::HTTP_OK, __('Logged out succesfully.'));

        return $this->wrapResponse(Response::HTTP_NOT_FOUND, __('Logged out failed.'));
    }

    private function wrapResponse(int $code, string $message, ?array $resource = []): JsonResponse
    {
        $result = [
            'code' => $code,
            'message' => $message
        ];

        if (count($resource))
            $result = array_merge(
                $result,
                [
                    'resource' => $resource['data'],
                    'token' => $resource['token'],
                    'token_type' => 'Bearer',
                ]
            );

        return response()->json($result);
    }
}

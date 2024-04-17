<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UserRequest;
use App\Http\Resources\v1\UserResource;
use App\Traits\UserTrait;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use UserTrait;

    /**
     * @throws ErrorException
     */
    public function update(UserRequest $request): JsonResponse
    {
        if ($this->updateUser($request))
        {
            $user = (new UserResource($request->user()))
                ->response()
                ->getData(true);

            return $this->wrapResponse(Response::HTTP_OK, __('User updated successfully.'), $user);

        }

        throw new ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws ErrorException
     */
    public function changePassword(UserRequest $request): JsonResponse
    {
        if ($this->updatePassword($request))
        {
            return $this->wrapResponse(Response::HTTP_OK, __('The password updated successfully.'));
        }

        throw new ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function detail(Request $request): JsonResponse
    {
        $user = (new UserResource($request->user()))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Your profile'), $user);
    }

    private function wrapResponse(int $code, string $message, ?array $resource = []): JsonResponse
    {
        $result = [
            'code' => $code,
            'message' => $message
        ];

        if (count($resource)) $result = array_merge($result, ['resource' => $resource]);

        return response()->json($result);
    }
}

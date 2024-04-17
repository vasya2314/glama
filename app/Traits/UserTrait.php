<?php

namespace App\Traits;

use App\Http\Requests\v1\UserRequest;
use Illuminate\Support\Facades\Hash;

trait UserTrait
{
    public function updateUser(UserRequest $request): bool
    {
        if ($request->user()->update($request->validatedUser())) return true;
        return false;
    }

    public function updatePassword(UserRequest $request): bool
    {
        $data = $request->validated();
        return (bool)$request->user()->update(['password' => Hash::make($data['new_password'])]);
    }

}

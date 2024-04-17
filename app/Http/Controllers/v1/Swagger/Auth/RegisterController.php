<?php

namespace App\Http\Controllers\v1\Swagger\Auth;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/register",
 *     summary="Регистрация пользователя",
 *     tags={"Пользователь"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/UserRegisterRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Ok",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="code", type="string", example="201"),
 *                     @OA\Property(property="message", type="string", example="Account has been successfully registered, please check your email to verify your account."),
 *                 )
 *             }
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="code", type="string", example="500"),
 *                     @OA\Property(property="message", type="string", example="Your account failed to register."),
 *                 )
 *             }
 *         ),
 *     ),
 * ),
 */
class RegisterController extends Controller
{
}

<?php

namespace App\Http\Controllers\v1\Swagger\Auth;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/forgot-password",
 *     summary="Отправка письма с подтверждением E-mail",
 *     tags={"Пользователь"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="email", type="email", example="lebedeb@grampus-studio.ru"),
 *                 )
 *             }
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="code", type="string", example="200"),
 *                     @OA\Property(property="message", type="string", example="We have emailed your password reset link."),
 *                 )
 *             }
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Some Error"),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="message",
 *                     type="array",
 *                     @OA\Items(type="string", example="Some Error")
 *                 )
 *             )
 *         )
 *     ),
 * ),
 * @OA\Post(
 *     path="/reset-password",
 *     summary="Сброс пароля",
 *     tags={"Пользователь"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="token", type="email", example="ebe5abaaabcb9c3da6859340541d0d674c87bc4386f48902a6149b23dec7a090"),
 *                     @OA\Property(property="email", type="email", example="lebedeb@grampus-studio.ru"),
 *                     @OA\Property(property="password", type="string", example="123456789_"),
 *                     @OA\Property(property="password_confirmation", type="string", example="123456789_"),
 *                 )
 *             }
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="code", type="string", example="200"),
 *                     @OA\Property(property="message", type="string", example="Password reset successfully"),
 *                 )
 *             }
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="code", type="string", example="500"),
 *                     @OA\Property(property="message", type="string", example="Some error"),
 *                 )
 *             }
 *         ),
 *     ),
 * ),
 */
class PasswordController extends Controller
{
}

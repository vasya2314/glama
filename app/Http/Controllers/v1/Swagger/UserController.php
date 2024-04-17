<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\patch(
 *     path="/user/update-user",
 *     summary="Изменение данных пользователя",
 *     tags={"Пользователь"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="User updated successfully."),
 *             @OA\Property(property="response", ref="#/components/schemas/UserResponse"),
 *             @OA\Property(property="token", type="string", example="1|32SuQYzh3jCFDoVYgk8bubmx6V12hTShaSDtRIlKc9518c76"),
 *             @OA\Property(property="token_type", type="string", example="Bearer"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to get service, please try again."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="message",
 *                     type="array",
 *                     @OA\Items(type="string", example="Failed to get service, please try again.")
 *                 )
 *             )
 *         )
 *     ),
 * ),
 * @OA\patch(
 *     path="/user/change-password",
 *     summary="Изменение пароля",
 *     tags={"Пользователь"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/UserChangePasswordRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="The password updated successfully."),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to get service, please try again."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="message",
 *                     type="array",
 *                     @OA\Items(type="string", example="Failed to get service, please try again.")
 *                 )
 *             )
 *         )
 *     ),
 * ),
 * @OA\Get(
 *     path="/user/detail",
 *     summary="Детальная информация о авторизованном пользователе",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Your profile"),
 *             @OA\Property(property="response", ref="#/components/schemas/UserResponse"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated"),
 *         )
 *     ),
 * ),
 */
class UserController extends Controller
{
}

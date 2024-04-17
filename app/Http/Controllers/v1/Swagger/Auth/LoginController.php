<?php

namespace App\Http\Controllers\v1\Swagger\Auth;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/login",
 *     summary="Вход в аккаунт",
 *     tags={"Пользователь"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="email", type="email", example="lebedeb@grampus-studio.ru"),
 *                     @OA\Property(property="password", type="string", example="123456789_"),
 *                 )
 *             }
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Logged in succesfully"),
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
 *             @OA\Property(property="message", type="string", example="The credentials do not match."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="message",
 *                     type="array",
 *                     @OA\Items(type="string", example="The credentials do not match.")
 *                 )
 *             )
 *         )
 *     ),
 * ),
 * @OA\Post(
 *      path="/logout",
 *      summary="Выход из аккаунта",
 *      tags={"Пользователь"},
 *
 *      @OA\Response(
 *          response=200,
 *          description="Ok",
 *          @OA\JsonContent(
 *              @OA\Property(property="code", type="string", example="200"),
 *              @OA\Property(property="message", type="string", example="Logged out succesfully"),
 *          ),
 *      ),
 *
 *      @OA\Response(
 *           response=404,
 *           description="Not found",
 *           @OA\JsonContent(
 *               @OA\Property(property="code", type="string", example="404"),
 *               @OA\Property(property="message", type="string", example="Logged out failed."),
 *           ),
 *      ),
 *
 *      @OA\Response(
 *           response=401,
 *           description="Not found",
 *           @OA\JsonContent(
 *               @OA\Property(property="message", type="string", example="Unauthenticated."),
 *           ),
 *      ),
 *  ),
 */
class LoginController extends Controller
{
}

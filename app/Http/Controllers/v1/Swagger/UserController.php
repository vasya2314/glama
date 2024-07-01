<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\patch(
 *     path="/user/update-user",
 *     summary="Изменение данных пользователя",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
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
 *     security={{ "bearerAuth": {} }},
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
 *
 * @OA\Get(
 *     path="/user/balance/{balanceType}",
 *     description="Типы: main,reward",
 *     summary="Получение баланса пользователя",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *        description="Тип счета",
 *        in="path",
 *        name="balanceType",
 *        required=true,
 *        example="main",
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Ok"),
 *             @OA\Property(property="reource", type="object",
 *                 @OA\Property(property="balance", type="float", example="2000.00"),
 *             ),
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
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 * ),
 * @OA\Get(
 *     path="/agency/users/child-users",
 *     summary="Получение всех дочерних пользователей",
 *     tags={"Агентский пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="All users"),
 *             @OA\Property(property="resource", type="object",
 *                 @OA\Property(property="data", type="array",
 *                     @OA\Items(ref="#/components/schemas/UserResponse"),
 *                 ),
 *                 oneOf={
 *                     @OA\Schema(ref="#/components/schemas/Pagination"),
 *                 },
 *             )
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
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 * ),
 * @OA\Post(
 *     path="/agency/users/create-user",
 *     summary="Создание агентского аккаунта",
 *     tags={"Агентский пользователь"},
 *     security={{ "bearerAuth": {} }},
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
 * @OA\Get(
 *     path="/agency/users/{user}/attach-user",
 *     summary="Получение баланса пользователя",
 *     tags={"Агентский пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *        description="ID пользователя",
 *        in="path",
 *        name="user",
 *        required=true,
 *        example="2",
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="You have successfully submitted a request to add a user to your agency",
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Bad Request"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 * ),
 * @OA\Post(
 *     path="/agency/user/login",
 *     summary="Вход в дочерний аккаунт",
 *     tags={"Агентский пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="user_id", type="integer", example="2"),
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
 *     path="/user/withdrawal-money-main",
 *     summary="Вывод денег с основного счета",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *            @OA\Property(property="amount", type="number", example="200000"),
 *            @OA\Property(property="contract_id", type="number", example="1"),
 *        ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="code", type="string", example="200"),
 *                     @OA\Property(property="message", type="string", example="Ok"),
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
 *                     @OA\Property(property="message", type="string", example="Error"),
 *                 )
 *             }
 *         ),
 *     ),
 * ),
 * @OA\Post(
 *     path="/user/withdrawal-money-reward",
 *     summary="Вывод денег со счета вознаграждений",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *            @OA\Property(property="amount", type="number", example="200000"),
 *            @OA\Property(property="reward_contract_id", type="number", example="1"),
 *        ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="code", type="string", example="200"),
 *                     @OA\Property(property="message", type="string", example="Ok"),
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
 *                     @OA\Property(property="message", type="string", example="Error"),
 *                 )
 *             }
 *         ),
 *     ),
 * ),
 */
class UserController extends Controller
{
}

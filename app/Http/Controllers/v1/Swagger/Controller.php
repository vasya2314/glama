<?php

namespace App\Http\Controllers\v1\Swagger;

/**
 * @OA\Info(
 *     title="GLama documentation API",
 *     version="1.0.0"
 * )
 * @OA\PathItem(path="/api/v1/"),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 * @OA\Server(
 *     description="Laravel Swagger API server",
 *     url="http://192.168.0.88/api/v1"
 * )
 * @OA\Tag(
 *     name="Пользователь",
 * )
 * @OA\Tag(
 *     name="Админ",
 * )
 * @OA\Tag(
 *     name="Агентский пользователь",
 * )
 * @OA\Tag(
 *     name="Клиенты",
 * )
 * @OA\Tag(
 *     name="Тикеты",
 * )
 * @OA\Tag(
 *     name="Договор",
 * )
 * @OA\Tag(
 *     name="Транзакции",
 * )
 * @OA\Tag(
 *     name="Закрывающие документы",
 * )
 */
class Controller extends \App\Http\Controllers\Controller
{
    //
}

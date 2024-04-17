<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/tickets/{ticket}/messages",
 *     summary="Получение сообщений в тикете",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID тикета",
 *         in="path",
 *         name="ticket",
 *         required=true,
 *         example="1",
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="All messages"),
 *             @OA\Property(property="resource", type="object",
 *                 @OA\Property(property="data", type="array",
 *                     @OA\Items(ref="#/components/schemas/MessageResponse"),
 *                 ),
 *                 oneOf={
 *                     @OA\Schema(ref="#/components/schemas/Pagination"),
 *                 },
 *             )
 *        )
 *     )
 * ),
 * @OA\Post(
 *     path="/tickets/{ticket}/messages",
 *     summary="Создание сообщение в конкретном тикете",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID тикета",
 *         in="path",
 *         name="ticket",
 *         required=true,
 *         example="1",
 *     ),
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/MessageStoreRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="201"),
 *             @OA\Property(property="message", type="string", example="Message created successfully."),
 *             @OA\Property(property="resource", type="string", ref="#/components/schemas/MessageResponse"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Some error"),
 *         ),
 *     ),
 * ),
 * @OA\Patch(
 *     path="/messages/{message}",
 *     summary="Изменение сообщения в конкретном тикете",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID сообщения",
 *         in="path",
 *         name="message",
 *         required=true,
 *         example="1",
 *     ),
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/MessageUpdateRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Message updated successfully."),
 *             @OA\Property(property="resource", type="string", ref="#/components/schemas/MessageResponse"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Some error"),
 *         ),
 *     ),
 * ),
 * @OA\Delete(
 *     path="/messages/{message}",
 *     summary="Удаление сообщения",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID сообщения",
 *         in="path",
 *         name="message",
 *         required=true,
 *         example="1",
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Error",
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Message deleted successfully."),
 *        )
 *     )
 * ),
 */
class MessageController extends Controller
{
}

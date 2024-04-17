<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/tickets",
 *     summary="Получение всех тикетов пользователя",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
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
 *             @OA\Property(property="message", type="string", example="All tickets."),
 *             @OA\Property(property="resource", type="object",
 *                 @OA\Property(property="data", type="array",
 *                     @OA\Items(ref="#/components/schemas/TicketResponse"),
 *                 ),
 *                 oneOf={
 *                     @OA\Schema(ref="#/components/schemas/Pagination"),
 *                 },
 *             )
 *        )
 *     )
 * ),
 * @OA\Post(
 *     path="/tickets",
 *     summary="Создание тикета",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/TicketStoreRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
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
 *
 *     @OA\Response(
 *         response=201,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="201"),
 *             @OA\Property(property="message", type="string", example="Ticket created successfully."),
 *             @OA\Property(property="resource", ref="#/components/schemas/TicketResponse"),
 *         ),
 *     ),
 * ),
 * @OA\Get(
 *     path="/tickets/{ticket}",
 *     summary="Получение конкретного тикета",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *        description="ID тикета",
 *        in="path",
 *        name="ticket",
 *        required=true,
 *        example="2",
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
 *             @OA\Property(property="message", type="string", example="Detail ticket"),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/TicketResponse"),
 *        )
 *     )
 * ),
 * @OA\Patch(
 *     path="/tickets/{ticket}/change-status",
 *     summary="Изменение статуса тикета",
 *     tags={"Тикеты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="close|open"),
 *         ),
 *     ),
 *
 *     @OA\Parameter(
 *        description="ID тикета",
 *        in="path",
 *        name="ticket",
 *        required=true,
 *        example="2",
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
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="The ticket updated successfully."),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/TicketResponse"),
 *        )
 *     )
 * ),
 * @OA\Delete(
 *     path="/tickets/{ticket}",
 *     summary="Удаление тикета",
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
 *         response=403,
 *         description="Error",
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
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="The ticket deleted successfully."),
 *        )
 *     )
 * ),
 */
class TicketController extends Controller
{
}

<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/contracts",
 *     summary="Получение всех договоров пользователя",
 *     tags={"Договор"},
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
 *             @OA\Property(property="message", type="string", example="All contracts."),
 *             @OA\Property(property="resource", type="array",
 *                 @OA\Items(ref="#/components/schemas/ContractResponse"),
 *             )
 *         )
 *     )
 * ),
 * @OA\Post(
 *     path="/contracts",
 *     summary="Создание договора",
 *     tags={"Договор"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/ContractStoreRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="201"),
 *             @OA\Property(property="message", type="string", example="Contract created successfully."),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/ContractResponse"),
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
 * @OA\Get (
 *     path="/contracts/{contract}",
 *     summary="Получение конкретного договра",
 *     tags={"Договор"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID договра",
 *         in="path",
 *         name="contract",
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
 *             @OA\Property(property="message", type="string", example="Detail contract"),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/ContractResponse"),
 *        )
 *     )
 * ),
 * @OA\Patch (
 *     path="/contracts/{contract}",
 *     summary="Изменение договора",
 *     tags={"Договор"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID договра",
 *         in="path",
 *         name="contract",
 *         required=true,
 *         example="1",
 *     ),
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/ContractUpdateRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Contract updated successfully."),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/ContractResponse"),
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
 *     path="/contracts/{contract}",
 *     summary="Удаление договора (!!!!ОТКЛЮЧЕНО!!!!)",
 *     tags={"Договор"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID договора",
 *         in="path",
 *         name="contract",
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
 *             @OA\Property(property="message", type="string", example="Contract deleted successfully."),
 *        )
 *     )
 * ),
 * @OA\Get (
 *     path="/contracts/{contract}/pdf",
 *     summary="Получение PDF договора",
 *     tags={"Договор"},
 *
 *     @OA\Parameter(
 *         description="ID договра",
 *         in="path",
 *         name="contract",
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
 *     )
 * ),
 */
class ContractController extends Controller
{
}

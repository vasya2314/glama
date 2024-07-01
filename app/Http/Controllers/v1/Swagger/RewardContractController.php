<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/reward-contracts",
 *     summary="Получение всех договоров пользователя",
 *     tags={"Договор на вознаграждение"},
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
 *                 @OA\Items(ref="#/components/schemas/RewardContractResponse"),
 *             )
 *         )
 *     )
 * ),
 * @OA\Post(
 *     path="/reward-contracts",
 *     summary="Создание договора",
 *     tags={"Договор на вознаграждение"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(ref="#/components/schemas/RewardContractStoreRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="201"),
 *             @OA\Property(property="message", type="string", example="Contract created successfully."),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/RewardContractResponse"),
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
 *     path="/reward-contracts/{rewardContract}",
 *     summary="Получение конкретного договра",
 *     tags={"Договор на вознаграждение"},
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
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/RewardContractResponse"),
 *        )
 *     )
 * ),
 * @OA\Patch (
 *     path="/reward-contracts/{rewardContract}",
 *     summary="Изменение договора",
 *     tags={"Договор на вознаграждение"},
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
 *         @OA\JsonContent(ref="#/components/schemas/RewardContractUpdateRequest"),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Contract updated successfully."),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/RewardContractResponse"),
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
 *     path="/reward-contracts/{rewardContract}",
 *     summary="Удаление договора (!!!!ОТКЛЮЧЕНО!!!!)",
 *     tags={"Договор на вознаграждение"},
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
 *     path="/reward-contracts/{rewardContract}/pdf",
 *     summary="Получение PDF договора",
 *     tags={"Договор на вознаграждение"},
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
class RewardContractController extends Controller
{
}

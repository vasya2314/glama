<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/closing-documents",
 *     summary="Получение всех закрывающих документов пользователя",
 *     tags={"Закрывающие документы"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="All closing documents."),
 *             @OA\Property(property="resource", type="object",
 *                 @OA\Property(property="data", type="array",
 *                     @OA\Items(ref="#/components/schemas/ClosingDocumentResponse"),
 *                 ),
 *                 oneOf={
 *                     @OA\Schema(ref="#/components/schemas/Pagination"),
 *                 },
 *             )
 *         ),
 *     ),

 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Some error"),
 *         ),
 *     ),
 * ),
 * @OA\Post(
 *     path="/user/closing-act/get",
 *     summary="Получить закрывающий акт",
 *     tags={"Закрывающие документы"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="closing_act_id",
 *                 format="iteger",
 *                 title="ID закрывающего акта",
 *                 description="ID закрывающего акта",
 *                 example="6",
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Возвращается File через stream",
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
 * @OA\Post(
 *     path="/user/closing-invoice/get",
 *     summary="Получить закрывающую счет фактуру",
 *     tags={"Закрывающие документы"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="closing_invoice_id",
 *                 format="iteger",
 *                 title="ID закрывающей счет - фактуры",
 *                 description="ID закрывающей счет - фактуры",
 *                 example="7",
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Возвращается File через stream",
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
 */
class ClosingDocumentController extends Controller
{

}

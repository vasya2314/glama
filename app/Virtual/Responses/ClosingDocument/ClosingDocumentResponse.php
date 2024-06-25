<?php

namespace App\Virtual\Responses\ClosingDocument;

/**
 * @OA\Schema(
 *     title="Возвращаемый закрывающий документ",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         format="iteger",
 *         title="ID",
 *         description="ID",
 *         example="1",
 *     ),
 *     @OA\Property(property="contract", type="object",
 *         @OA\Property(property="id", type="integer", example="1"),
 *         @OA\Property(property="name", type="string", example="ООО iHor хостинг"),
 *     ),
 *     @OA\Property(property="closing_invoice", type="object",
 *         @OA\Property(property="id", type="integer", example="2"),
 *         @OA\Property(property="date_generated", type="datetime", example="2024-06-22 13:55:21"),
 *         @OA\Property(property="amount", type="float", example="1000.00"),
 *         @OA\Property(property="amount_nds", type="float", example="1200.00"),
 *     ),
 *     @OA\Property(property="closing_act", type="object",
 *         @OA\Property(property="id", type="integer", example="2"),
 *         @OA\Property(property="date_generated", type="datetime", example="2024-06-22 13:55:21"),
 *         @OA\Property(property="number", type="string", example="2024/05/31-16"),
 *         @OA\Property(property="amount", type="float", example="2000.00"),
 *         @OA\Property(property="amount_nds", type="float", example="1400.00"),
 *     ),
 *     @OA\Property(property="created_at", type="datetime", example="2024-06-22 13:40:21"),
 * )
 */




class ClosingDocumentResponse
{
}

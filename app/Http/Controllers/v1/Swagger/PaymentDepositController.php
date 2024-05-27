<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     description="Указывать стоимость в копейках. QR код комиссия - 1.7%. По карте - 2.59%",
 *     path="/user/deposit/payment",
 *     summary="Пополнение баланса аккаунта для физических лиц",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *              @OA\Property(property="amount_deposit", type="integer", example="30000"),
 *              @OA\Property(property="amount", type="integer", example="30510"),
 *              @OA\Property(property="contract_id", type="integer", example="322"),
 *              @OA\Property(property="method_type", type="string", example="qr|card"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Ok"),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/DepositPaymentResponse"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 * ),
 */

class PaymentDepositController extends Controller
{
}

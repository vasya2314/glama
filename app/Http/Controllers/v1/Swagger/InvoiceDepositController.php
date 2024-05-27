<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Facades\YandexDirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\YandexDirectPaymentRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @OA\Post(
 *     description="Указывать стоимость в копейках",
 *     path="/user/deposit/invoice",
 *     summary="Пополнение баланса аккаунта для юридических лиц",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *              @OA\Property(property="amount", type="integer", example="30510"),
 *              @OA\Property(property="contract_id", type="integer", example="322"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Some message"),
 *             @OA\Property(property="resource", type="object", ref="#/components/schemas/DepositInvoiceResponse"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 * ),
 */
class InvoiceDepositController extends Controller
{
}

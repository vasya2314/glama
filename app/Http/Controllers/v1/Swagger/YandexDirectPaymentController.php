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
 *     path="/user/yandex-direct/deposit",
 *     summary="Пополнение Яндекс аккаунта",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *              @OA\Property(property="amount_deposit", type="integer", example="30000"),
 *              @OA\Property(property="amount", type="integer", example="30510"),
 *              @OA\Property(property="client_id", type="integer", example="322"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Balance has been updated succesfully"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 *
 *     @OA\Response(
 *         response=503,
 *         description="Error",
 *     ),
 * ),
 */
class YandexDirectPaymentController extends Controller
{
}

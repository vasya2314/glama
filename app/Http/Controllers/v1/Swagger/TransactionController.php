<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *      path="/transactions",
 *      description="Возможна фильтрация по полям type:deposit, deposit_invoice, deposit_yandex_account, removal, cashback. Дате date_start, date_end. Формат даты: 2024-05-18",
 *      summary="Получить все транзакции пользователя",
 *      tags={"Транзакции"},
 *      security={{ "bearerAuth": {} }},
 *
 *      @OA\Response(
 *          response=200,
 *          description="Ok",
 *          @OA\JsonContent(
 *              @OA\Property(property="code", type="string", example="200"),
 *              @OA\Property(property="message", type="string", example="All transactions"),
 *              @OA\Property(property="resource", type="object",
 *                  @OA\Property(property="data", type="array",
 *                      @OA\Items(ref="#/components/schemas/TransactionResponse"),
 *                  ),
 *                  oneOf={
 *                      @OA\Schema(ref="#/components/schemas/Pagination"),
 *                  },
 *              )
 *          ),
 *      ),
 *
 *      @OA\Response(
 *          response=500,
 *          description="Error",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Some error"),
 *          ),
 *      ),
 *  ),
 */
class TransactionController extends Controller
{
}

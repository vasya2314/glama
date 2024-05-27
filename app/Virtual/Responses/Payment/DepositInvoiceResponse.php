<?php

namespace App\Virtual\Responses\Payment;

/**
 * @OA\Schema(
 *     title="Возвращаемый ответ от Тинькоф при оплате для юридических лиц",
 *     type="object",
 *
 *     @OA\Property(property="pdfUrl", type="string", example="https://example.com/qwetq"),
 *     @OA\Property(property="invoiceId", type="string", example="d8327c28-4a8e-4084-93ea-a94b7bd144c5"),
 * )
 */

class DepositInvoiceResponse
{
}

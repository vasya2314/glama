<?php

namespace App\Virtual\Responses\Payment;

/**
 * @OA\Schema(
 *     title="Возвращаемый ответ от Тинькоф при оплате для физических лиц",
 *     type="object",
 *
 *     @OA\Property(property="Success", type="boolean", example=true),
 *     @OA\Property(property="ErrorCode", type="string", example="0"),
 *     @OA\Property(property="Message", type="string", example="OK"),
 *     @OA\Property(property="TerminalKey", type="string", example="1708325547610DEMO"),
 *     @OA\Property(property="Data", type="string", example="https://qr.nspk.ru/AD10004MV5CJ3D638358RO81129CE63P?type=02&bank=100000000004&sum=30510&cur=RUB&crc=B754"),
 *     @OA\Property(property="OrderId", type="string", example="12764079-6e5b-4d22-817c-6420aa3804ab"),
 *     @OA\Property(property="PaymentId", type="integer", example=4471678787)
 * )
 */

class DepositPaymentResponse
{
}

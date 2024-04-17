<?php

namespace App\Virtual\Requests\Client;

/**
 * @OA\Schema(
 *     title="Изменение клиента",
 *     type="object",
 *     required={"contract_id"},
 *     @OA\Property(
 *         property="contract_id",
 *         format="iteger",
 *         title="ID контракта",
 *         description="ID контракта",
 *         example="150",
 *     ),
 * )
 */
class ClientUpdateRequest
{
}

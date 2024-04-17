<?php

namespace App\Virtual\Requests\Contract;

/**
 * @OA\Schema(
 *     title="Изменение договора",
 *
 *     @OA\Property(
 *         property="client_id",
 *         format="iteger",
 *         title="ID клиента",
 *         description="ID клиента",
 *         example="null",
 *     ),
 *     oneOf={
 *         @OA\Schema(ref="#/components/schemas/ContractNaturalPersonEntityRequest"),
 *     },
 * )
 */
class ContractUpdateRequest
{
}

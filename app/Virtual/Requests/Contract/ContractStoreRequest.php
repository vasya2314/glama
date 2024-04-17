<?php

namespace App\Virtual\Requests\Contract;

/**
 * @OA\Schema(
 *     title="Создание договора",
 *     required={"contract_type"},
 *
 *     @OA\Property(
 *         property="contract_type",
 *         format="string",
 *         title="Тип контракта",
 *         description="Тип контракта - (legal_entity, individual_entrepreneur, natural_person)",
 *         example="natural_person",
 *     ),
 *     oneOf={
 *         @OA\Schema(ref="#/components/schemas/ContractNaturalPersonEntityRequest"),
 *     },
 * )
 */
class ContractStoreRequest
{
}

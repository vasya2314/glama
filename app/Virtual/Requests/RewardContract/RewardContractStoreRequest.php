<?php

namespace App\Virtual\Requests\RewardContract;

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
 *         @OA\Schema(ref="#/components/schemas/RewardContractNaturalPersonEntityRequest"),
 *     },
 * )
 */
class RewardContractStoreRequest
{
}

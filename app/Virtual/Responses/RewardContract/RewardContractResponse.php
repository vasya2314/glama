<?php

namespace App\Virtual\Responses\RewardContract;

/**
 * @OA\Schema(
 *     title="Возвращаемый договор",
 *     type="object",
 * )
 */

class RewardContractResponse
{
    /**
     * @OA\Property(
     *     property="id",
     *     format="integer",
     *     title="ID документа",
     *     description="ID документа",
     *     example="15",
     * )
     */
    public $id;

    /**
     * @OA\Property(
     *     property="user_id",
     *     format="integer",
     *     title="ID пользователя",
     *     description="ID пользователя",
     *     example="20",
     * )
     */
    public $userId;

    /**
     * @OA\Property(
     *     property="display_name",
     *     format="string",
     *     title="Отображаемое имя договора",
     *     description="Отображаемое имя договора",
     *     example="ООО iHor хостинг",
     * )
     */
    public $displayName;

    /**
     * @OA\Property(
     *     property="contract_type",
     *     format="integer",
     *     title="Тип контракта",
     *     description="Тип контракта",
     *     example="natural_person",
     * )
     */
    public $contractType;

    /**
     * @OA\Property(property="contract_details", format="object", ref="#/components/schemas/RewardNaturalPersonContractResponse")
     */
    public $contractDetails;

    /**
     * @OA\Property(
     *     property="created_at",
     *     format="date",
     *     title="Дата создания",
     *     description="Дата создания",
     *     example="2024-04-02 09:53:15",
     * )
     */
    public $createdAt;

}

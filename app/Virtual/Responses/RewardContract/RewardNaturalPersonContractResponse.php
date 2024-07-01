<?php

namespace App\Virtual\Responses\RewardContract;

/**
 * @OA\Schema(
 *     title="Договор - физическое лицо (ответ)",
 *     type="object",
 * )
 */

class RewardNaturalPersonContractResponse
{
    /**
     * @OA\Property(
     *     property="id",
     *     format="integer",
     *     title="ID",
     *     description="ID",
     *     example="15",
     * )
     */
    public $id;

    /**
     * @OA\Property(
     *     property="lastname",
     *     format="string",
     *     title="Фамилия",
     *     description="Фамилия",
     *     example="Лебедев",
     * )
     */
    public $lastname;

    /**
     * @OA\Property(
     *     property="firstname",
     *     format="string",
     *     title="Имя",
     *     description="Имя",
     *     example="Игорь",
     * )
     */
    public $firstname;

    /**
     * @OA\Property(
     *     property="surname",
     *     format="string",
     *     title="Отчество",
     *     description="Отчество",
     *     example="Алексеевич",
     * )
     */
    public $surname;

    /**
     * @OA\Property(
     *     property="inn",
     *     format="string",
     *     title="ИНН",
     *     description="ИНН",
     *     example="1234567890",
     * )
     */
    public $inn;

    /**
     * @OA\Property(
     *     property="address",
     *     format="string",
     *     title="Адрес",
     *     description="Адрес",
     *     example="г. Вологда, ул. Герцена, д. 96 кв. 12",
     * )
     */
    public $address;

    /**
     * @OA\Property(
     *     property="pick_up",
     *     format="string",
     *     title="Как вы будете забирать документы",
     *     description="Как вы будете забирать документы (not_need, need_original, need_email)",
     *     example="not_need",
     * )
     */
    public $pickUp;

}

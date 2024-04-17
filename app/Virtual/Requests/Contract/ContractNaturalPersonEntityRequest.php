<?php

namespace App\Virtual\Requests\Contract;

/**
 * @OA\Schema(
 *     title="Создание договора для физического лица",
 *     type="object",
 *     required={"lastname","firstname", "inn", "address", "pick_up"},
 * )
 */
class ContractNaturalPersonEntityRequest
{
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
     *     type="number",
     *     title="ИНН",
     *     description="ИНН",
     *     example="1234567890",
     * )
     */
    public $inn;

    /**
     * @OA\Property(
     *     property="address",
     *     type="string",
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

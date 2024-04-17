<?php

namespace App\Virtual\Requests\User;

/**
 * @OA\Schema(
 *     title="Изменение пользователя",
 *     type="object",
 * )
 */
class UserUpdateRequest
{
    /**
     * @OA\Property(
     *     property="name",
     *     format="string",
     *     title="Имя пользователя",
     *     description="Имя пользователя",
     *     example="Игорь",
     * )
     */
    public $name;

    /**
     * @OA\Property(
     *     property="lastname",
     *     format="string",
     *     title="Фамилия пользователя",
     *     description="Фамилия пользователя",
     *     example="Лебедев",
     * )
     */
    public $lastname;

    /**
     * @OA\Property(
     *     property="phone",
     *     format="string",
     *     title="Номер телефона пользователя",
     *     description="Номер телефона пользователя",
     *     example="+79673506988",
     * )
     */
    public $phone;

    /**
     * @OA\Property(
     *     property="contact_email",
     *     format="string",
     *     title="Контактынй email пользователя",
     *     description="Контактный email пользователя",
     *     example="lebedeb@grampus-studio.ru",
     * )
     */
    public $contactEmail;

}

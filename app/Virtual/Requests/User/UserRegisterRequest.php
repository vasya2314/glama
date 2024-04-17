<?php

namespace App\Virtual\Requests\User;

/**
 * @OA\Schema(
 *     title="Создание пользователя",
 *     type="object",
 *     required={"email", "contact_email", "password", "password_confirmation"},
 * )
 */
class UserRegisterRequest
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
     *     property="email",
     *     format="string",
     *     title="Email пользователя",
     *     description="Email пользователя",
     *     example="lebedeb@grampus-studio.ru",
     * )
     */
    public $email;

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

    /**
     * @OA\Property(
     *     property="password",
     *     format="string",
     *     title="Пароль пользователя",
     *     description="Пароль пользователя",
     *     example="123456789_",
     * )
     */
    public $password;

    /**
     * @OA\Property(
     *     property="password_confirmation",
     *     format="string",
     *     title="Подтверждение пароля пользователя",
     *     description="Подтверждение пароля пользователя",
     *     example="123456789_",
     * )
     */
    public $passwordConfirmation;
}

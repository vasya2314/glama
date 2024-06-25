<?php

namespace App\Virtual\Responses\User;

/**
 * @OA\Schema(
 *     title="Возвращаемый пользователь",
 *     type="object",
 * )
 */
class UserResponse
{
    /**
     * @OA\Property(
     *     property="id",
     *     format="integer",
     *     title="ID пользователя",
     *     description="ID пользователя",
     *     example="10",
     * )
     */
    public $id;

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
     *     property="email_verified_at",
     *     format="date",
     *     title="Дата подтверждения email",
     *     description="Дата подтверждения email",
     *     example="2024-04-02 09:53:15",
     * )
     */
    public $emailVerifiedAt;

    /**
     * @OA\Property(
     *     property="role",
     *     format="integer",
     *     title="Роль пользователя",
     *     description="Роль пользователя",
     *     example="1",
     * )
     */
    public $role;

    /**
     * @OA\Property(
     *     property="user_type",
     *     format="string",
     *     title="Тип пользователя",
     *     description="Тип пользователя. Варианты: agency, simple",
     *     example="simple",
     * )
     */
    public $userType;

    /**
     * @OA\Property(
     *     property="parent_id",
     *     format="integer",
     *     title="ID родительского аккунта",
     *     description="ID родительского аккунта",
     *     example="null",
     * )
     */
    public $parentId;

    /**
     * @OA\Property(
     *     property="created_at",
     *     format="date",
     *     title="Дата регистрации",
     *     description="Дата регистрации",
     *     example="2024-04-02 09:53:15",
     * )
     */
    public $createdAt;
}

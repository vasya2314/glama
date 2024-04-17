<?php

namespace App\Virtual\Requests\User;

/**
 * @OA\Schema(
 *     title="Изменение пароля",
 *     type="object",
 *     required={"current_password", "new_password", "new_password_confirmation"},
 * )
 */
class UserChangePasswordRequest
{
    /**
     * @OA\Property(
     *     property="current_password",
     *     format="string",
     *     title="Пароль пользователя",
     *     description="Пароль пользователя",
     *     example="123456789_",
     * )
     */
    public $currentPassword;

    /**
     * @OA\Property(
     *     property="new_password",
     *     format="string",
     *     title="Новый пароль пользователя",
     *     description="Новый пароль пользователя",
     *     example="123456789_!",
     * )
     */
    public $newPassword;

    /**
     * @OA\Property(
     *     property="new_password_confirmation",
     *     format="string",
     *     title="Новый пароль пользователя (подтверждение)",
     *     description="Новый пароль пользователя подтверждение",
     *     example="123456789_!",
     * )
     */
    public $newPasswordConfirmation;

}

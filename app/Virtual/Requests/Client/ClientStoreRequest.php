<?php

namespace App\Virtual\Requests\Client;

/**
 * @OA\Schema(
 *     title="Создание клиента",
 *     type="object",
 *     required={"contract_id", "account_name", "Login", "FirstName", "LastName", "Currency", "Grants", "Notification", "Settings", "TinInfo"},
 *     @OA\Property(
 *         property="contract_id",
 *         format="iteger",
 *         title="ID контракта",
 *         description="ID контракта",
 *         example="150",
 *     ),
 *     @OA\Property(
 *         property="account_name",
 *         format="string",
 *         title="Glama название аккаунта",
 *         description="Glama название аккаунта",
 *         example="Some name",
 *     ),
 *     @OA\Property(
 *         property="login",
 *         format="string",
 *         title="Login",
 *         description="Login",
 *         example="iHorLogin",
 *     ),
 * )
 */
class ClientStoreRequest
{
}

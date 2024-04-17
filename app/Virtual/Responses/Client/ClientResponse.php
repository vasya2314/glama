<?php

namespace App\Virtual\Responses\Client;

/**
 * @OA\Schema(
 *     title="Возвращаемый клиент",
 *     type="object",
 * )
 */

class ClientResponse
{
    /**
     * @OA\Property(
     *     property="id",
     *     format="integer",
     *     title="ID клиента (внутри базы)",
     *     description="ID клиента (внутри базы)",
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
     *     example="10",
     * )
     */
    public $userId;

    /**
     * @OA\Property(
     *     property="contract_id",
     *     format="integer",
     *     title="ID контракта",
     *     description="ID контракта",
     *     example="10",
     * )
     */
    public $contractId;


    /**
     * @OA\Property(
     *     property="login",
     *     format="string",
     *     title="Логин клиента",
     *     description="Логин клиента",
     *     example="lebedeb228",
     * )
     */
    public $login;

    /**
     * @OA\Property(
     *     property="password",
     *     format="string",
     *     title="Пароль клиента",
     *     description="Пароль клиента",
     *     example="WEDAsvasrtr124",
     * )
     */
    public $password;

    /**
     * @OA\Property(
     *     property="client_id",
     *     format="integer",
     *     title="ID клиента (внутри яндекса)",
     *     description="ID клиента (внутри яндекса)",
     *     example="10500",
     * )
     */
    public $clientId;

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

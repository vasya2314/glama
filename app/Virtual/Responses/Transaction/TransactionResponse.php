<?php

namespace App\Virtual\Responses\Transaction;

/**
 * @OA\Schema(
 *     title="Возвращаемая транзакция",
 *     type="object",
 * )
 */

class TransactionResponse
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
     *     property="contract_id",
     *     format="integer",
     *     title="ID договора",
     *     description="ID договора",
     *     example="10",
     * )
     */
    public $contractId;

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
     *     property="type",
     *     format="string",
     *     title="Тип транзакции",
     *     description="Тип транзакции",
     *     example="deposit",
     * )
     */
    public $type;

    /**
     * @OA\Property(
     *     property="status",
     *     format="string",
     *     title="Статус",
     *     description="Статус",
     *     example="CONFIRMED",
     * )
     */
    public $status;

    /**
     * @OA\Property(
     *     property="amount_deposit",
     *     format="integer",
     *     title="Сумма без комиссии",
     *     description="Сумма с комиссией",
     *     example="30000",
     * )
     */
    public $amountDeposit;

    /**
     * @OA\Property(
     *     property="amount",
     *     format="integer",
     *     title="Сумма с комиссией",
     *     description="Сумма Сумма с комиссией",
     *     example="30510",
     * )
     */
    public $amount;

    /**
     * @OA\Property(
     *     property="method_type",
     *     format="integer",
     *     title="Тип пополнения",
     *     description="Тип пополнения",
     *     example="qr",
     * )
     */
    public $methodType;

    /**
     * @OA\Property(
     *     property="balance_account_type",
     *     format="string",
     *     title="Тип счета",
     *     description="Тип счета",
     *     example="main",
     * )
     */
    public $balanceAccountType;

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

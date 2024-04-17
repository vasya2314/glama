<?php

namespace App\Virtual\Responses\Ticket;

/**
 * @OA\Schema(
 *     title="Возвращаемый тикет",
 *     type="object",
 * )
 */

class TicketResponse
{
    /**
     * @OA\Property(
     *     property="id",
     *     format="integer",
     *     title="ID тикета",
     *     description="ID тикета",
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
     *     property="title",
     *     format="string",
     *     title="Заголовок",
     *     description="Заголовок",
     *     example="Ничего не работает",
     * )
     */
    public $title;

    /**
     * @OA\Property(
     *     property="status",
     *     format="string",
     *     title="Статус тикета",
     *     description="Статус тикета. Имеет два статуса: close|open",
     *     example="close|open",
     * )
     */
    public $status;

    /**
     * @OA\Property(
     *     property="assigned_to",
     *     format="integer",
     *     title="ID ответсветнного админа на тикет",
     *     description="ID ответсветнного админа на тикет",
     *     example="101",
     * )
     */
    public $assignedTo;

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

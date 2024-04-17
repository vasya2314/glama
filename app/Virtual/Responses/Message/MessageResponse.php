<?php

namespace App\Virtual\Responses\Message;

/**
 * @OA\Schema(
 *     title="Возвращаемое сообщение",
 *     type="object",
 * )
 */

class MessageResponse
{
    /**
     * @OA\Property(
     *     property="id",
     *     format="integer",
     *     title="ID сообщения",
     *     description="ID сообщения",
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
     *     property="ticket_id",
     *     format="integer",
     *     title="ID тикета",
     *     description="ID тикета",
     *     example="101",
     * )
     */
    public $ticketId;

    /**
     * @OA\Property(format="object", ref="#/components/schemas/Media")
     */
    public $media;

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

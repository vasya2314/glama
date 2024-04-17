<?php

namespace App\Virtual\Requests\Message;

/**
 * @OA\Schema(
 *     title="Обновление сообщения в тикете",
 *     type="object",
 *     required={"message"},
 * )
 */
class MessageUpdateRequest
{
    /**
     * @OA\Property(
     *     property="message",
     *     format="string",
     *     title="Сообщение",
     *     description="Сообщение",
     *     example="У меня проблема...",
     * )
     */
    public $message;
}

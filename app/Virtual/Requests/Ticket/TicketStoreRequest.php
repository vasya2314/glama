<?php

namespace App\Virtual\Requests\Ticket;

/**
 * @OA\Schema(
 *     title="Создание тикета",
 *     type="object",
 *     required={"message"},
 * )
 */
class TicketStoreRequest
{
    /**
     * @OA\Property(
     *     property="title",
     *     format="string",
     *     title="Заголовок тикета",
     *     description="Заголовок тикета",
     *     example="Не могу пополнить баланс",
     * )
     */
    public $title;

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

    /**
     * @OA\Property(
     *     property="files[]",
     *     format="file",
     *     title="Прикрепленные файлы",
     *     description="Прикрепленные файлы",
     * )
     */
    public $files;
}

<?php

namespace App\Virtual\Requests\Message;

/**
 * @OA\Schema(
 *     title="Создание сообщения в тикете",
 *     type="object",
 *     required={"message"},
 * )
 */
class MessageStoreRequest
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

<?php

namespace App\Virtual\Requests\Client;

/**
 * @OA\Schema(
 *     title="Получние клиента",
 *     type="object",
 *     required={"FieldNames"},
 *      @OA\Property(
 *          property="FieldNames",
 *          type="array",
 *          @OA\Items(type="string", example="AccountQuality"),
 *      )
 * )
 */
class ClientAllRequest
{
}

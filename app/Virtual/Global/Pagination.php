<?php

namespace App\Virtual\Global;

/**
 * @OA\Schema(
 *     title="Пагинация",
 *     type="object",
 * )
 * @OA\Property(property="links", type="object",
 *     @OA\Property(property="first", type="string", example="http://192.168.0.88/api/v1/tickets?page=1"),
 *     @OA\Property(property="last", type="string", example="http://192.168.0.88/api/v1/tickets?page=1"),
 *     @OA\Property(property="prev", type="null"),
 *     @OA\Property(property="next", type="null")
 * ),
 * @OA\Property(property="meta", type="object",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=1),
 *     @OA\Property(property="links", type="array",
 *         @OA\Items(
 *             @OA\Property(property="url", type="null"),
 *             @OA\Property(property="label", type="string", example="&laquo; Назад"),
 *             @OA\Property(property="active", type="boolean", example=false)
 *         ),
 *         @OA\Items(
 *             @OA\Property(property="url", type="string", example="http://192.168.0.88/api/v1/tickets?page=1"),
 *             @OA\Property(property="label", type="string", example="1"),
 *             @OA\Property(property="active", type="boolean", example=true)
 *         ),
 *         @OA\Items(
 *             @OA\Property(property="url", type="null"),
 *             @OA\Property(property="label", type="string", example="Вперёд &raquo;"),
 *             @OA\Property(property="active", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Property(property="path", type="string", example="http://192.168.0.88/api/v1/tickets"),
 *     @OA\Property(property="per_page", type="integer", example=30),
 *     @OA\Property(property="to", type="integer", example=1),
 *     @OA\Property(property="total", type="integer", example=1)
 * )
*/
class Pagination
{
}

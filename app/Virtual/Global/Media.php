<?php

namespace App\Virtual\Global;

/**
 * @OA\Schema(
 *   title="Медиа",
 *   type="object",
 *   properties={
 *     @OA\Property(property="9bf3b899-94f7-4329-8da9-5b69d76265d4", type="object",
 *          @OA\Property(property="name", type="string", example = "Безымянный",),
 *          @OA\Property(property="file_name", type="string", example = "Безымянный.png",),
 *          @OA\Property(property="uuid", type="string", example = "9bf3b899-94f7-4329-8da9-5b69d76265d4",),
 *          @OA\Property(property="preview_url", type="string", example = "",),
 *          @OA\Property(property="original_url", type="string", example = "http://192.168.0.88/storage/5/Безымянный.png",),
 *          @OA\Property(property="order", type="integer", example = 1,),
 *          @OA\Property(property="custom_properties", type="array", @OA\Items(type="string"), example = "[]"),
 *          @OA\Property(property="extension", type="string", example = "png",),
 *          @OA\Property(property="size", type="integer", example = 7080),
 *     ),
 *     @OA\Property(property="9bf3b899-94f7-4329-8da9-asfasfasf", type="object",
 *          @OA\Property(property="name", type="string", example = "Безымянный",),
 *          @OA\Property(property="file_name", type="string", example = "Безымянный.png",),
 *          @OA\Property(property="uuid", type="string", example = "9bf3b899-94f7-4329-8da9-asfasfasf",),
 *          @OA\Property(property="preview_url", type="string", example = "",),
 *          @OA\Property(property="original_url", type="string", example = "http://192.168.0.88/storage/5/Безымянный.png",),
 *          @OA\Property(property="order", type="integer", example = 1,),
 *          @OA\Property(property="custom_properties", type="array", @OA\Items(type="string"), example = "[]"),
 *          @OA\Property(property="extension", type="string", example = "png",),
 *          @OA\Property(property="size", type="integer", example = 7080),
 *     ),
 *   },
 * )
 */
class Media
{
}

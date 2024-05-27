<?php

namespace App\Http\Controllers\v1\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/clients",
 *     summary="Добавление клиента (аккаунт Яндекс)",
 *     tags={"Клиенты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(ref="#/components/schemas/ClientStoreRequest"),
 *      ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="201"),
 *             @OA\Property(property="message", type="string", example="Client was created successfully."),
 *             @OA\Property(property="resource", type="string", ref="#/components/schemas/ClientResponse"),
 *         ),
 *     ),
 * ),
 *
 * @OA\Patch(
 *     path="/clients/{client}",
 *     summary="Изменение клиента (аккаунт Яндекс)",
 *     tags={"Клиенты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID клиента",
 *         in="path",
 *         name="client",
 *         required=true,
 *         example="15",
 *     ),
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(ref="#/components/schemas/ClientUpdateRequest"),
 *      ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Error",
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Client was updated successfully."),
 *             @OA\Property(property="resource", type="string", ref="#/components/schemas/ClientResponse"),
 *         ),
 *     ),
 * ),
 *
 * @OA\Get(
 *      path="/clients",
 *      summary="Получить всех клиентов текущего пользователя",
 *      tags={"Клиенты"},
 *      security={{ "bearerAuth": {} }},
 *
 *      @OA\Response(
 *          response=200,
 *          description="Ok",
 *          @OA\JsonContent(
 *              @OA\Property(property="code", type="string", example="200"),
 *              @OA\Property(property="message", type="string", example="All clients"),
 *              @OA\Property(property="resource", type="object",
 *                  @OA\Property(property="data", type="array",
 *                      @OA\Items(ref="#/components/schemas/ClientResponse"),
 *                  ),
 *                  oneOf={
 *                      @OA\Schema(ref="#/components/schemas/Pagination"),
 *                  },
 *              )
 *          ),
 *      ),
 *
 *      @OA\Response(
 *          response=500,
 *          description="Error",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Some error"),
 *          ),
 *      ),
 *  ),
 *
 * @OA\Get(
 *     path="/clients/{client}",
 *     summary="Получить конкретного клиента текущего авторизованного пользователя",
 *     tags={"Клиенты"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="ID клиента",
 *         in="path",
 *         name="client",
 *         required=true,
 *         example="15",
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Forbidden"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="code", type="string", example="200"),
 *             @OA\Property(property="message", type="string", example="Detail client"),
 *             @OA\Property(property="resource", type="string", ref="#/components/schemas/ClientResponse"),
 *         ),
 *     ),
 * ),
 * @OA\Get(
 *      path="/clients/{client}/update/campaigns-qty",
 *      summary="Обновление счетсчика количества компании на аккаунте Яндекс",
 *      tags={"Клиенты"},
 *      security={{ "bearerAuth": {} }},
 *
 *      @OA\Parameter(
 *          description="ID клиента",
 *          in="path",
 *          name="client",
 *          required=true,
 *          example="15",
 *      ),
 *
 *      @OA\Response(
 *          response=403,
 *          description="Error",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Forbidden"),
 *          ),
 *      ),
 *
 *      @OA\Response(
 *          response=200,
 *          description="Error",
 *          @OA\JsonContent(
 *              @OA\Property(property="code", type="string", example="200"),
 *              @OA\Property(property="message", type="string", example="The company counter has been successfully updated"),
 *              @OA\Property(property="resource", type="string", ref="#/components/schemas/ClientResponse"),
 *          ),
 *      ),
 *  ),
 */
class ClientController extends Controller
{
}

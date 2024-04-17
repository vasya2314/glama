<?php

namespace App\Virtual\Requests\Client;

/**
 * @OA\Schema(
 *     title="Создание клиента",
 *     type="object",
 *     required={"contract_id", "glama_account_name", "Login", "FirstName", "LastName", "Currency", "Grants", "Notification", "Settings", "TinInfo"},
 *     @OA\Property(
 *         property="contract_id",
 *         format="iteger",
 *         title="ID контракта",
 *         description="ID контракта",
 *         example="150",
 *     ),
 *     @OA\Property(
 *         property="glama_account_name",
 *         format="string",
 *         title="Glama название аккаунта",
 *         description="Glama название аккаунта",
 *         example="Some name",
 *     ),
 *
 *     @OA\Property(
 *         property="params",
 *         format="object",
 *         @OA\Property(
 *             property="Login",
 *             type="string",
 *             description="User login",
 *             example="Client-login"
 *         ),
 *         @OA\Property(
 *             property="FirstName",
 *             type="string",
 *             description="User's first name",
 *             example="peter"
 *         ),
 *         @OA\Property(
 *             property="LastName",
 *             type="string",
 *             description="User's last name",
 *             example="son"
 *         ),
 *         @OA\Property(
 *             property="Currency",
 *             type="string",
 *             description="User's currency",
 *             example="RUB"
 *         ),
 *         @OA\Property(
 *             property="Grants",
 *             type="array",
 *             description="Array of user grants",
 *             @OA\Items(type="string"),
 *             example="[]",
 *         ),
 *         @OA\Property(
 *             property="Notification",
 *             type="object",
 *             description="User's notification details",
 *             @OA\Property(
 *                 property="Lang",
 *                 type="string",
 *                 description="Language for notifications",
 *                 example="RU"
 *             ),
 *             @OA\Property(
 *                 property="Email",
 *                 type="string",
 *                 description="User's email",
 *                 example="client-1@mail.ru"
 *             ),
 *             @OA\Property(
 *                 property="EmailSubscriptions",
 *                 type="array",
 *                 description="User's email subscriptions",
 *                 @OA\Items(
 *                     @OA\Property(
 *                         property="Option",
 *                         type="string",
 *                         description="Subscription option",
 *                         example="RECEIVE_RECOMMENDATIONS"
 *                     ),
 *                     @OA\Property(
 *                         property="Value",
 *                         type="string",
 *                         description="Subscription value",
 *                         example="NO"
 *                     )
 *                 )
 *             )
 *         ),
 *         @OA\Property(
 *             property="Settings",
 *             type="array",
 *             description="User's settings",
 *             @OA\Items(
 *                 @OA\Property(
 *                     property="Option",
 *                     type="string",
 *                     description="Setting option",
 *                     example="CORRECT_TYPOS_AUTOMATICALLY"
 *                 ),
 *                 @OA\Property(
 *                     property="Value",
 *                     type="string",
 *                     description="Setting value",
 *                     example="NO"
 *                 )
 *             )
 *         ),
 *         @OA\Property(
 *             property="TinInfo",
 *             type="object",
 *             description="User's Tin information",
 *             @OA\Property(
 *                 property="TinType",
 *                 type="string",
 *                 description="Type of Tin",
 *                 example="PHYSICAL"
 *             ),
 *             @OA\Property(
 *                 property="Tin",
 *                 type="integer",
 *                 description="User's Tin number",
 *                 example=505021556592
 *             )
 *         )
 *     ),
 * )
 */
class ClientStoreRequest
{
}

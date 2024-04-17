<?php

namespace App\Http\Controllers\v1\Swagger\Auth;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/email/verification-notification",
 *     summary="Повторная отправка на подтверждние почты (при регистрации отправляется автоматически один раз)",
 *     tags={"Пользователь"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Ok",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Verification link sent"),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Your account has already verified"),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="message",
 *                     type="array",
 *                     @OA\Items(type="string", example="Your account has already verified")
 *                 )
 *             )
 *         )
 *     ),
 * ),
 *
 * @OA\Get(
 *      path="/verify-email/{id}/{hash}",
 *      summary="Подтверждение почты.",
 *      tags={"Пользователь"},
 *      security={{ "bearerAuth": {} }},
 *
 *      @OA\Parameter(
 *          description="ID пользователя",
 *          in="path",
 *          name="id",
 *          required=true,
 *          example="1",
 *      ),
 *
 *      @OA\Parameter(
 *          description="HASH из письма",
 *          in="path",
 *          name="hash",
 *          required=true,
 *          example="2f4da12153d6b0655475f2fb79e05fe09f88d818",
 *       ),
 *
 *      @OA\Response(
 *          response=200,
 *          description="Ok",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Your account has already verified"),
 *          ),
 *      ),
 *
 *      @OA\Response(
 *          response=500,
 *          description="Error",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Some error"),
 *              @OA\Property(
 *                  property="errors",
 *                  type="object",
 *                  @OA\Property(
 *                      property="message",
 *                      type="array",
 *                      @OA\Items(type="string", example="Some error")
 *                  )
 *              )
 *          )
 *      ),
 *  ),
 *
 */
class EmailVerificationController extends Controller
{
}

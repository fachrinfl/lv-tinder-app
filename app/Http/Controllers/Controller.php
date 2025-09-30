<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Tinder App API",
 *     description="API for Tinder-like dating app MVP",
 *     @OA\Contact(
 *         email="admin@example.com"
 *     )
 * )
 * 
 * 
 * @OA\SecurityScheme(
 *     securityScheme="UserIdHeader",
 *     type="apiKey",
 *     in="header",
 *     name="X-User-Id",
 *     description="User ID for testing (UUID format)"
 * )
 * 
 * @OA\Schema(
 *     schema="Person",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=116),
 *     @OA\Property(property="name", type="string", example="Ayu"),
 *     @OA\Property(property="age", type="integer", example=26),
 *     @OA\Property(property="location", type="string", example="Jakarta"),
 *     @OA\Property(
 *         property="pictures",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="url", type="string", example="https://.../ayu1.jpg"),
 *             @OA\Property(property="order", type="integer", example=1)
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Picture",
 *     type="object",
 *     @OA\Property(property="url", type="string", example="https://.../ayu1.jpg"),
 *     @OA\Property(property="order", type="integer", example=1)
 * )
 * 
 * @OA\Schema(
 *     schema="InteractionResult",
 *     type="object",
 *     @OA\Property(property="person_id", type="integer", example=116),
 *     @OA\Property(property="action", type="string", enum={"like", "dislike"}),
 *     @OA\Property(property="status", type="string", enum={"recorded", "duplicate"})
 * )
 * 
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     @OA\Property(property="code", type="string", example="ERROR_CODE"),
 *     @OA\Property(property="message", type="string", example="Error description"),
 *     @OA\Property(property="data", type="object")
 * )
 */
abstract class Controller
{
}
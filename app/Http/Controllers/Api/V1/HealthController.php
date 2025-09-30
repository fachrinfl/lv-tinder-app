<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Health",
 *     description="Health check endpoints"
 * )
 */
class HealthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/v1/health",
     *     summary="Health check endpoint",
     *     tags={"Health"},
     *     @OA\Response(
     *         response=200,
     *         description="API is healthy",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="SUCCESS"),
     *             @OA\Property(property="message", type="string", example="API is healthy"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="time", type="string", example="2025-09-29T10:05:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'code' => 'SUCCESS',
            'message' => 'API is healthy',
            'data' => [
                'time' => Carbon::now()->toISOString()
            ]
        ]);
    }
}

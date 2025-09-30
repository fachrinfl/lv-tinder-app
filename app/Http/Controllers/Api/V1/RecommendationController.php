<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @OA\Tag(
 *     name="Recommendations",
 *     description="Recommendation endpoints"
 * )
 */
class RecommendationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/v1/recommendations",
     *     summary="Get list of recommended people",
     *     tags={"Recommendations"},
     *     security={{"UserIdHeader":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer", default=1),
     *         description="Page number"
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer", default=20),
     *         description="Items per page"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of recommended people",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="SUCCESS"),
     *             @OA\Property(property="message", type="string", example="List of recommended people"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Person")
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=20),
     *                     @OA\Property(property="total", type="integer", example=85),
     *                     @OA\Property(property="total_pages", type="integer", example=5)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        $page = $request->input('page', 1);
        $perPage = min($request->input('per_page', 20), 50);

        $interactedPersonIds = Interaction::where('user_id', $userId)
            ->pluck('person_id')
            ->toArray();

        $query = Person::with('pictures')
            ->whereNotIn('id', $interactedPersonIds);

        $query->orderBy('location')
            ->orderBy('created_at', 'desc');

        $total = $query->count();
        $people = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $totalPages = ceil($total / $perPage);

        return response()->json([
            'code' => 'SUCCESS',
            'message' => 'List of recommended people',
            'data' => [
                'items' => $people->map(function ($person) {
                    return [
                        'id' => $person->id,
                        'name' => $person->name,
                        'age' => $person->age,
                        'location' => $person->location,
                        'pictures' => $person->pictures->map(function ($picture) {
                            return [
                                'url' => $picture->url,
                                'order' => $picture->order
                            ];
                        })->toArray()
                    ];
                }),
                'meta' => [
                    'page' => (int) $page,
                    'per_page' => (int) $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages
                ]
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/v1/people/{id}",
     *     summary="Get person detail",
     *     tags={"Recommendations"},
     *     security={{"UserIdHeader":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Person ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person detail",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="SUCCESS"),
     *             @OA\Property(property="message", type="string", example="Person detail"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Person"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="PERSON_NOT_FOUND"),
     *             @OA\Property(property="message", type="string", example="Person not found."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $person = Person::with('pictures')->find($id);

        if (!$person) {
            return response()->json([
                'code' => 'PERSON_NOT_FOUND',
                'message' => 'Person not found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'code' => 'SUCCESS',
            'message' => 'Person detail',
            'data' => [
                'id' => $person->id,
                'name' => $person->name,
                'age' => $person->age,
                'location' => $person->location,
                'pictures' => $person->pictures->map(function ($picture) {
                    return [
                        'url' => $picture->url,
                        'order' => $picture->order
                    ];
                })->toArray()
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Interaction;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Interactions",
 *     description="Interaction endpoints (like/dislike)"
 * )
 */
class InteractionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/v1/people/{id}/like",
     *     summary="Like a person",
     *     tags={"Interactions"},
     *     security={{"UserIdHeader":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Person ID"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Like recorded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="SUCCESS"),
     *             @OA\Property(property="message", type="string", example="Like recorded successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="person_id", type="integer", example=57),
     *                 @OA\Property(property="action", type="string", example="like"),
     *                 @OA\Property(property="status", type="string", example="recorded")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Duplicate like",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="DUPLICATE_LIKE"),
     *             @OA\Property(property="message", type="string", example="You have already liked this person."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="person_id", type="integer", example=57),
     *                 @OA\Property(property="action", type="string", example="like"),
     *                 @OA\Property(property="status", type="string", example="duplicate")
     *             )
     *         )
     *     )
     * )
     */
    public function like(Request $request, $id)
    {
        $userId = $request->input('user_id');
        
        $person = Person::find($id);
        if (!$person) {
            return response()->json([
                'code' => 'PERSON_NOT_FOUND',
                'message' => 'Person not found.',
                'data' => []
            ], 404);
        }

        $existingInteraction = Interaction::where('user_id', $userId)
            ->where('person_id', $id)
            ->first();

        if ($existingInteraction) {
            if ($existingInteraction->action === 'like') {
                return response()->json([
                    'code' => 'DUPLICATE_LIKE',
                    'message' => 'You have already liked this person.',
                    'data' => [
                        'person_id' => $id,
                        'action' => 'like',
                        'status' => 'duplicate'
                    ]
                ], 409);
            } else {
                $existingInteraction->update(['action' => 'like']);
            }
        } else {
            Interaction::create([
                'user_id' => $userId,
                'person_id' => $id,
                'action' => 'like'
            ]);
        }

        return response()->json([
            'code' => 'SUCCESS',
            'message' => 'Like recorded successfully',
            'data' => [
                'person_id' => $id,
                'action' => 'like',
                'status' => 'recorded'
            ]
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/v1/people/{id}/dislike",
     *     summary="Dislike a person",
     *     tags={"Interactions"},
     *     security={{"UserIdHeader":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Person ID"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Dislike recorded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="SUCCESS"),
     *             @OA\Property(property="message", type="string", example="Dislike recorded successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="person_id", type="integer", example=58),
     *                 @OA\Property(property="action", type="string", example="dislike"),
     *                 @OA\Property(property="status", type="string", example="recorded")
     *             )
     *         )
     *     )
     * )
     */
    public function dislike(Request $request, $id)
    {
        $userId = $request->input('user_id');
        
        $person = Person::find($id);
        if (!$person) {
            return response()->json([
                'code' => 'PERSON_NOT_FOUND',
                'message' => 'Person not found.',
                'data' => []
            ], 404);
        }

        $existingInteraction = Interaction::where('user_id', $userId)
            ->where('person_id', $id)
            ->first();

        if ($existingInteraction) {
            if ($existingInteraction->action === 'dislike') {
                return response()->json([
                    'code' => 'DUPLICATE_DISLIKE',
                    'message' => 'You have already disliked this person.',
                    'data' => [
                        'person_id' => $id,
                        'action' => 'dislike',
                        'status' => 'duplicate'
                    ]
                ], 409);
            } else {
                $existingInteraction->update(['action' => 'dislike']);
            }
        } else {
            Interaction::create([
                'user_id' => $userId,
                'person_id' => $id,
                'action' => 'dislike'
            ]);
        }

        return response()->json([
            'code' => 'SUCCESS',
            'message' => 'Dislike recorded successfully',
            'data' => [
                'person_id' => $id,
                'action' => 'dislike',
                'status' => 'recorded'
            ]
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/v1/likes",
     *     summary="Get list of liked people",
     *     tags={"Interactions"},
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
     *         description="List of liked people",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="SUCCESS"),
     *             @OA\Property(property="message", type="string", example="List of liked people"),
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
     *                     @OA\Property(property="total", type="integer", example=1),
     *                     @OA\Property(property="total_pages", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function likes(Request $request)
    {
        $userId = $request->input('user_id');
        $page = $request->input('page', 1);
        $perPage = min($request->input('per_page', 20), 50);

        $likedPersonIds = Interaction::where('user_id', $userId)
            ->where('action', 'like')
            ->pluck('person_id')
            ->toArray();

        $query = Person::with('pictures')
            ->whereIn('id', $likedPersonIds)
            ->orderBy('created_at', 'desc');

        $total = $query->count();
        $people = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $totalPages = ceil($total / $perPage);

        return response()->json([
            'code' => 'SUCCESS',
            'message' => 'List of liked people',
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
}

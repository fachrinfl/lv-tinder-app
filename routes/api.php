<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\RecommendationController;
use App\Http\Controllers\Api\V1\InteractionController;

Route::get('/v1/health', [HealthController::class, 'index']);


Route::middleware(['validate.user.id'])->prefix('v1')->group(function () {
    Route::get('/recommendations', [RecommendationController::class, 'index']);
    Route::get('/people/{id}', [RecommendationController::class, 'show']);
    
    Route::post('/people/{id}/like', [InteractionController::class, 'like']);
    Route::post('/people/{id}/dislike', [InteractionController::class, 'dislike']);
    Route::get('/likes', [InteractionController::class, 'likes']);
});

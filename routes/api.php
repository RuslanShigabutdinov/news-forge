<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthorController,
    NewsController,
    RubricController,
    AuthController
};

Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::apiResource('authors', AuthorController::class);

    Route::get('news/search', [NewsController::class, 'search'])->name('news.search');
    Route::apiResource('news', NewsController::class);

    Route::apiResource('rubrics', RubricController::class);
    Route::get('rubrics/{rubric}/news',  [RubricController::class, 'getNewsWithChildren'])->name('rubric.newsWithChildren');

    Route::post('logout', [AuthController::class,'logout']);
});

Route::post('register', [AuthController::class,'register'])->middleware('throttle:6,1');
Route::post('login',    [AuthController::class,'login'])->middleware('throttle:6,1');

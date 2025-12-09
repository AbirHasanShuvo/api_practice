<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogCategoryController;
use App\Http\Controllers\API\BlogPostController;
use App\Http\Controllers\API\CommentController;
use Dom\Comment;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

//protected and required route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout', [AuthController::class, 'logout']);

    //blog category routes
    Route::apiResource('categories', BlogCategoryController::class)->middleware('role:admin');

    //for post API
    Route::apiResource('posts', BlogPostController::class)->middleware('role:admin|author');

    //for creating comments
    Route::get('comments', [CommentController::class, 'index'])->name('index')->middleware(['role:admin']);
    // Route::post();
    Route::post('comments/change-status', [CommentController::class, 'changeStatus'])->name('changeStatus')->middleware('role:admin');
    Route::apiResource('comments', CommentController::class);
});

Route::get('categories', [BlogCategoryController::class, 'index']);
Route::get('posts', [BlogPostController::class, 'index'])->name('index');


//updated

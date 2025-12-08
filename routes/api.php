<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogCategoryController;
use App\Http\Controllers\API\BlogPostController;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
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
});

Route::get('categories', [BlogCategoryController::class, 'index']);
Route::get('posts', [BlogPostController::class, 'index'])->name('index');


//updated

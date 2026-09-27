<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register'])->middleware('throttle:register');
Route::post('/login', [UserController::class, 'login'])->middleware('throttle:login');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [UserController::class, 'profile']);

    Route::post('/profile/cv', [UserController::class, 'uploadCv'])
        ->middleware('throttle:job-writes');

    Route::get('/profile/cv', [UserController::class, 'downloadCv']);
    Route::delete('/profile/cv', [UserController::class, 'destroyCv']);

    Route::post('/logout', [UserController::class, 'logout']);
    Route::apiResource('jobs', JobController::class);

    Route::post('/jobs/{job}/like', [JobController::class, 'like'])
        ->middleware('throttle:job-writes');

    Route::delete('/jobs/{job}/like', [JobController::class, 'unlike']);

    Route::get('/likes', [JobController::class, 'liked']);
    Route::get('/jobs/{job}/comments', [CommentController::class, 'index']);

    Route::post('/jobs/{job}/comments', [CommentController::class, 'store'])
        ->middleware('throttle:job-writes');

    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::patch('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    Route::get('/settings/jobs', [JobController::class, 'managed']);
    Route::get('/settings/users', [UserManagementController::class, 'index']);

    Route::delete('/settings/users/{user}', [UserManagementController::class, 'destroy'])
        ->middleware('throttle:job-writes');
});

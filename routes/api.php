<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(AuthMiddleware::class)->group(function () {
  Route::get('/users/current', [UserController::class, 'current']);
  Route::patch('/users/current', [UserController::class, 'update']);
  Route::delete('/users/logout', [UserController::class, 'logout']);
});

<?php

use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'login'])->middleware('throttle:4,1');
Route::post('register', [\App\Http\Controllers\Api\V1\Auth\RegisterController::class, 'register']);
Route::middleware(['auth:sanctum'])->group(function () {
    //elimina el token
    Route::post('logout', [\App\Http\Controllers\Api\V1\Auth\LogoutController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::prefix('/tasks')->group(function(){
        Route::controller(TaskController::class)->group(function(){
            Route::get('/index', 'index');
            Route::post('/store', 'store');
            Route::get('/edit/{id}', 'edit');
            Route::put('/update/{id}', 'update');
            Route::delete('/delete/{id}', 'delete');
            Route::put('/complete/{id}', 'complete');
        });
    });
});

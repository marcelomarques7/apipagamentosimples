<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\UserController;
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

Route::get('/ping', function(){
    return [
        'pong' => true,
    ];
});

Route::post('/cadastro', [AuthController::class, 'cadastro']);
Route::get('/users', [UserController::class, 'users']);
Route::get('/user/{id}', [UserController::class, 'user']);
Route::put('/user/{id}', [UserController::class, 'atualizar']);
Route::delete('/user/{id}', [UserController::class, 'deletar']);


Route::post('/transferir', [TransferenciaController::class, 'transferir']);
Route::get('/extrato/{id}', [TransferenciaController::class, 'extrato']);
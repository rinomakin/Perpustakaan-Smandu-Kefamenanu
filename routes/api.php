<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Jenis Buku API Routes
Route::prefix('jenis-buku')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\JenisBukuController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Api\JenisBukuController::class, 'store']);
    Route::get('/active', [App\Http\Controllers\Api\JenisBukuController::class, 'active']);
    Route::get('/{jenisBuku}', [App\Http\Controllers\Api\JenisBukuController::class, 'show']);
    Route::put('/{jenisBuku}', [App\Http\Controllers\Api\JenisBukuController::class, 'update']);
    Route::delete('/{jenisBuku}', [App\Http\Controllers\Api\JenisBukuController::class, 'destroy']);
});

<?php

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

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/storage/list', [\App\Http\Controllers\Api\FilesStorageController::class, 'index']);
    Route::post('/store/file', [\App\Http\Controllers\Api\FilesStorageController::class, 'store']);
    Route::get('/file/download', [\App\Http\Controllers\Api\FilesStorageController::class, 'downloadFile']);
    Route::patch('/file/rename', [App\Http\Controllers\Api\FilesStorageController::class, 'update']);
    Route::delete('/file/delete', [\App\Http\Controllers\Api\FilesStorageController::class, 'destroy']);
    Route::get('/folder/show', [\App\Http\Controllers\Api\FoldersController::class, 'showFolder']);
    Route::post('/folder/create', [\App\Http\Controllers\Api\FoldersController::class, 'storeFolder']);
});

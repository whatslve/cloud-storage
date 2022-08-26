<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/storage', [\App\Http\Controllers\FilesStorageController::class, 'index'])->name('storage');

    Route::get('/upload_show', [\App\Http\Controllers\FilesStorageController::class, 'create'])->name('upload_show');

    Route::get('/create_folder', [\App\Http\Controllers\FoldersController::class, 'createFolder'])->name('create_folder');

    Route::post('/store_folder', [\App\Http\Controllers\FoldersController::class, 'storeFolder']);

    Route::get('/folder/{id}', [\App\Http\Controllers\FoldersController::class, 'showFolder']);

    Route::get('/file/{id}', [\App\Http\Controllers\FilesStorageController::class, 'showFile']);

    Route::get('/file/delete/{id}', [\App\Http\Controllers\FilesStorageController::class, 'destroy']);

    Route::get('/file/rename/{id}', [\App\Http\Controllers\FilesStorageController::class, 'edit']);

    Route::post('/file/update', [\App\Http\Controllers\FilesStorageController::class, 'update']);

    Route::get('/file/download/{id}', [\App\Http\Controllers\FilesStorageController::class, 'downloadFile']);

    Route::post('/upload_file', [\App\Http\Controllers\FilesStorageController::class, 'storeFile']);
});

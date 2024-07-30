<?php

use App\Http\Controllers\Api\FanController;
use App\Http\Controllers\Api\KlubController;
use App\Http\Controllers\Api\LigaController;
use App\Http\Controllers\Api\PemainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route Liga Manual/Kupling/Gigi
// Route::get('liga', [LigaController::class, 'index']); //data keseluruhan
// Route::post('liga', [LigaController::class, 'store']); //menambahkan data
// Route::get('liga/{id}', [LigaController::class, 'show']); //menampilkan berdasarkan id
// Route::put('liga/{id}', [LigaController::class, 'update']); //mengedit liga
// Route::delete('liga/{id}', [LigaController::class, 'destroy']); //menghapus liga

// Route Liga Matic
Route::resource('liga', LigaController::class)->except('create', 'edit');
// Route Liga Matic
Route::resource('klub', KlubController::class)->except('create', 'edit');
// Route Pemain Matic
Route::resource('pemain', PemainController::class)->except('create', 'edit');
// Route Pemain Matic
Route::resource('fan', FanController::class)->except('create', 'edit');

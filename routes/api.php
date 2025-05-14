<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route login (tanpa middleware karena user belum login)
Route::post('/login', [ApiController::class, 'login']);

// Route yang butuh token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('loker/history/{id}', [ApiController::class, 'historyDatalog'])->name('api.loker.history');
    Route::get('loker', [ApiController::class, 'loker_akses'])->name('api.loker');
    Route::get('loker/{id}', [ApiController::class, 'show'])->name('api.loker.show');
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::put('/loker/hak-akses/{id}/{code}', [ApiController::class, 'updateStatus'])->name('api.loker.akses-update');

    // GET | /users/{user} | show | users.show

});

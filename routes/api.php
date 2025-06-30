<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ModelLokerController;



// Route login (tanpa middleware karena user belum login)
Route::post('/login', [ApiController::class, 'login']);

// Route yang butuh token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('loker/history/{id}/{user_id}', [ApiController::class, 'historyDatalog'])->name('api.loker.history');
    Route::get('loker', [ApiController::class, 'loker_akses'])->name('api.loker');
    Route::get('loker/{id}', [ApiController::class, 'show'])->name('api.loker.show');
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::put('/loker/update-status/{id}', [ModelLokerController::class, 'updateStatus'])->name('loker.status');
    Route::put('/loker/hak-hapus-akses/{id}/{loker_id}', [ModelLokerController::class, 'deleteStatusApi'])->name('loker.delete.akses');

    // Route::put('/loker/update-dashst/{id}/{user_id}', [ModelLokerController::class, 'updateStatusDash'])->name('loker.statusDash');
    // GET | /users/{user} | show | users.show

});

Route::get('/loker/hak-akses/{code}', [ApiController::class, 'updateStatusByCode'])->name('api.loker.akses-update');
Route::get('/relay', [ApiController::class, 'inforelay']);

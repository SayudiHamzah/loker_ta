<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModelLogController;
use App\Http\Controllers\ModelLokerController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('layout.app');
// });
// Route::get('/user', function () {
//     return view('user.index');
// });
Route::get('/loker', function () {
    return view('loker.index');
});

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('user', UserController::class)->except(['show']);
Route::put('/loker/hak-hapus-akses', [ModelLokerController::class, 'deleteStatus'])->name('loker.delete.akses');
Route::get('/loker/history/{id}', [ModelLokerController::class, 'history'])->name('loker.history');
Route::put('/loker/update-status/{id}', [ModelLokerController::class, 'updateStatus'])->name('loker.status');
Route::put('/loker/update-dashst/{id}/{user_id}', [ModelLokerController::class, 'updateStatusDash'])->name('loker.statusDash');
Route::get('/loker/hak-akses/{id}', [ModelLokerController::class, 'edit'])->name('loker.akses');
Route::put('/loker/hak-akses', [ModelLokerController::class, 'updateStatus'])->name('loker.akses-update');

// GET | /users/{user}/edit | edit | users.edit
// PUT | /users/{user} | update | users.update
Route::resource('loker', ModelLokerController::class);
Route::resource('datalog', ModelLogController::class)->except(['create','edit','update', 'destroy','store']);

Route::resource('manual', ModelLogController::class)->except(['create','edit','update', 'destroy','store']);
Route::get('/manual', [ManualController::class, 'index'])->name('manual');


// example route
// Method | URI | Action | Route Name
// GET | /users | index | users.index
// GET | /users/create | create | users.create
// POST | /users | store | users.store
// GET | /users/{user} | show | users.show
// GET | /users/{user}/edit | edit | users.edit
// PUT | /users/{user} | update | users.update
// DELETE | /users/{user} | destroy | users.destroy

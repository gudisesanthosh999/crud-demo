<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect('/login');
});

// Route::get('/login', [LoginController::class, 'show'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout']);

// Route::middleware('auth')->group(function () {
//     Route::view('/items', 'items');
// });

Route::view('/login', 'login')->name('login');
Route::view('/items', 'items');

// Route::get('/{any}', function () {
//     return view('items'); 
// })->where('any', '.*');

// Route::view('/', 'items');
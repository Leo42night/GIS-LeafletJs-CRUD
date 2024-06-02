<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlaceMapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();
Route::get('/', [PlaceMapController::class,'index'])->name('frontpage');

Route::get('/home', [PlaceMapController::class,'index'])->name('home');
Route::get('/places/data', [DataController::class,'places'])->name('place.data'); // DATA TABLE CONTROLLER
Route::get('/places/api', [DataController::class,'index'])->name('place.api'); // MAP CONTROLLER

Route::resource('places', PlaceController::class);

// Route::group(['middleware' => ['auth']], function () {
//     Route::resource('places', PlaceController::class);
// });

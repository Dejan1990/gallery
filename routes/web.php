<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AlbumController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/albums/create', [AlbumController::class, 'create'])->middleware('auth');
Route::post('/albums/store', [AlbumController::class, 'store'])->middleware('auth');

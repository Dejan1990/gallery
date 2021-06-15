<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\FollowController;


Route::get('/', [FrontendController::class, 'index']);
Route::get('/user/profile/{id}', [FrontendController::class, 'userAlbum'])->name('user.album');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/albums', [AlbumController::class, 'index'])->middleware('auth');
Route::get('/albums/create', [AlbumController::class, 'create'])->name('album.create')->middleware('auth');
Route::post('/albums/store', [AlbumController::class, 'store'])->middleware('auth');
Route::put('/albums/{album:id}/edit', [AlbumController::class, 'update'])->middleware('auth');
Route::delete('/albums/{id}/delete', [AlbumController::class, 'destroy'])->middleware('auth');
Route::get('/getalbums', [AlbumController::class, 'getAlbums'])->middleware('auth');

Route::get('/upload/images/{id}', [GalleryController::class, 'create'])->middleware('auth');
Route::post('/uploadImages', [GalleryController::class, 'upload'])->middleware('auth');
Route::get('/getimages', [GalleryController::class, 'images'])->middleware('auth');
Route::delete('/image/{image:id}', [GalleryController::class, 'destroy']);
Route::get('/albums/{slug}/{id}', [GalleryController::class, 'viewAlbum'])->name('view.album');

Route::post('/follow', [FollowController::class, 'followUnfollow'])->middleware('auth');
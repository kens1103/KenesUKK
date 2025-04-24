<?php

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// LOGIN & LOGOUT
Route::get('/register', [AuthController::class, 'registerForm'])->name('registerForm');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// GALERI
Route::get('/', [PhotoController::class, 'index'])->name('galeri');
Route::post('/upload', [PhotoController::class, 'store'])->name('upload');
Route::middleware(['auth'])->group(function () {
    Route::delete('/delete/{id}', [PhotoController::class, 'destroy'])->name('delete');
});
Route::get('/galeri/search', [PhotoController::class, 'search'])->name('galeri.search');
Route::middleware(['auth'])->group(function () {
    Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');
});
Route::get('/photos/create', [PhotoController::class, 'create'])->name('photos.create');

// LIKE & KOMEN
Route::get('/photo/{id}', [PhotoController::class, 'show'])->name('photo.show');
Route::middleware(['auth'])->group(function () {
    Route::post('/photo/{id}/comment', [PhotoController::class, 'addComment'])->name('photo.comment');
    Route::delete('/photo/comment/{id}', [PhotoController::class, 'deleteComment'])->name('photo.comment.delete');
    Route::post('/photo/{id}/like', [PhotoController::class, 'likePhoto'])->name('photo.like');
    Route::post('/comment/{id}/like', [PhotoController::class, 'likeComment'])->name('comment.like');
});

// USER
Route::get('/dashboard', [UserDashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::delete('/photos/{id}', [UserDashboardController::class, 'destroyPhoto'])->name('photos.destroy');
Route::delete('/comments/{id}', [UserDashboardController::class, 'destroyComment'])->name('comments.destroy');
Route::get('/users', [UserController::class, 'index'])->name('users.index');

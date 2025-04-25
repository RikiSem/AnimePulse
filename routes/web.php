<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErrorControler;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [IndexController::class, 'show'])->name('index');

Route::prefix('anime')->group(function () {
    Route::get('/show/{id}', [AnimeController::class, 'showOne'])->name('anime.show');
});

Route::prefix('review')->group(function () {
    Route::get('/show/{id}', [ReviewController::class, 'show'])->name('review.show');
});

Route::prefix('library')->group(function () {
    Route::get('/show', [LibraryController::class, 'show'])->name('library');
});

Route::prefix('video')->group(function () {
    Route::get('/show', [VideoController::class, 'show'])->name('video');
});

Route::prefix('forum')->group(function () {
    Route::get('/show', [ForumController::class, 'show'])->name('forum');
});
Route::prefix('about')->group(function () {
    Route::get('/show', [AboutController::class, 'show'])->name('about');
});

Route::middleware('auth')->controller(DashboardController::class)->group(function () {
    Route::get('/dashboard/', 'show')->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/dashboard/reviews',  'reviews')->middleware(['auth', 'verified'])->name('dashboard.reviews');
    Route::get('/dashboard/comments', 'comments')->middleware(['auth', 'verified'])->name('dashboard.comments');

    Route::prefix('admin')->group(function () {
        Route::get('/show', [AdminController::class, 'show'])->name('admin');
    });

    Route::prefix('user')->group(function () {
        Route::get('/show', [UserController::class, 'show'])->name('user');
        Route::get('/show/reviews', [UserController::class, 'showUserReviews'])->name('user.reviews');
        Route::get('/show/comments', [UserController::class, 'showUserComments'])->name('user.comments');
    });
});

Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'edit')->name('profile.edit');
    Route::patch('/profile', 'update')->name('profile.update');
    Route::delete('/profile', 'destroy')->name('profile.destroy');
});

Route::fallback([ErrorControler::class, 'show404Page']);

require __DIR__.'/auth.php';

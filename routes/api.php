<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\CheckRequestLocal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(CheckRequestLocal::class)->group(function () {
    Route::prefix('anime')->controller(AnimeController::class)->group(function () {
        Route::post('/get/one', 'getOne')->name('anime.get-one');
        Route::post('/get/for-user', 'getForUser')->name('anime.get');
        Route::post('/filter', 'filter')->name('anime.filter');
        Route::post('/tags', 'tags')->name('anime.get-tags');
        Route::prefix('list')->group(function () {
            Route::post('/add', 'addToList')->name('anime.add-to-list');
            Route::post('/remove', 'removeFromList')->name('anime.remove-from-list');
        });
    });

    Route::prefix('comments')->controller(CommentController::class)->group(function () {
        Route::prefix('/get')->group(function () {
            Route::post('/for-anime', 'getForAnime')->name('comments.get-for-anime');
            Route::post('/for-user', 'getForUser')->name('comments.get-for-user');
            Route::post('/for-review', 'getForReview')->name('comment.get-for-review');
        });
        Route::post('/create', 'create')->name('comment.create');
    });

    Route::prefix('review')->controller(ReviewController::class)->group(function () {
        Route::post('/get/for-anime', 'getForAnime')->name('review.get-for-anime');
        Route::post('/get/for-user', 'getUserReviews')->name('user.reviews');
        Route::post('/create', 'create')->name('review.create');
        Route::post('/update', 'update')->name('review.update');
        Route::post('/delete', 'delete')->name('review.delete');
        Route::post('/reject', 'reject')->name('review.reject');
        Route::post('/accept', 'accept')->name('review.accept');
    });

    Route::prefix('user')->group(function () {
        Route::post('get', [\App\Http\Controllers\UserController::class, 'getUser'])->name('user.get');
        Route::post('save/avatar', [\App\Http\Controllers\UserController::class, 'changeAvatar'])->name('user.save-avatar');
    });

    Route::prefix('library')->group(function () {
        Route::post('/all', [\App\Http\Controllers\LibraryController::class, 'all'])->name('library.all');
    });

    Route::prefix('reaction')->group(function () {
        Route::post('/create', [\App\Http\Controllers\ReactionController::class, 'create'])->name('reaction.create');
    });

    Route::prefix('favorite')->group(function () {
        Route::post('/add', [\App\Http\Controllers\FavoriteController::class, 'add'])->name('favorite.add');
    });
});




<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Models\Anime;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function comments(Request $request) {
        return Inertia::render('DashboardComments',
            [
                'pages' => Pages::$pages,
                'comments' => []
            ]
        );
    }

    public function reviews(Request $request) {
        return Inertia::render('DashboardReviews',
            [
                'pages' => Pages::$pages,
            ]
        );
    }

    public function show(Request $request)
    {
        return Inertia::render('Dashboard',
            [
                'pages' => Pages::$pages,
                'animeStatuses' => Anime::ANIME_STATUSES,
                'statusForUser' => Anime::ANIME_STATUS_FOR_USER,
            ]
        );
    }
}

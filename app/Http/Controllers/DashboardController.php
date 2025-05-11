<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\StudioRep;
use App\Models\Anime;
use App\Traits\PrepareFilterList;
use App\Traits\PrepareReleaseYearFilterList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use PrepareFilterList;
    protected AnimeRep  $animeRep;
    protected StudioRep $studioRep;
    public function __construct(AnimeRep $animeRep, StudioRep $studioRep) {
        $this->animeRep = $animeRep;
        $this->studioRep = $studioRep;
    }
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
                'animeTypes' => Anime::ANIME_TYPES_FILTER,
                'animeReleaseYears' => self::prepareList($this->animeRep->getAllUnicReleaseYears()->toArray(), 'Выберите год выхода'),
                'studios' => $this->studioRep->getAllStudioNames(),
            ]
        );
    }
}

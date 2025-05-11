<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\StudioRep;
use App\Traits\PrepareShowPageBody;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use PrepareShowPageBody;
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
        return Inertia::render(
            'Dashboard', 
            self::prepareForFilter(
                $this->animeRep,
                $this->studioRep,
            )
        );
    }
}

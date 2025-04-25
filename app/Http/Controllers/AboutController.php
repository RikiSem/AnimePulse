<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\ReviewRep;
use App\Http\Classes\Storage;
use App\Http\Classes\Texts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;

class AboutController extends Controller
{

    public const ABOUT_BG_IMG = 'about_bg.png';
    public const SOCIALS = [
        'tg' => [
            'link' => 'https://t.me/PulseOfAnime',
        ]
    ];

    public function show(Request $request) {
        return Inertia::render('AboutPage',[
            'social' => self::SOCIALS,
            'pages' => Pages::$pages,
            'text' => Texts::getAboutText(
                $request->getHttpHost(),
                AnimeRep::getAnimeCount(),
                ReviewRep::getReviewCount()
            ),
            'img' => Storage::SYSTEM_IMG_PATH . self::ABOUT_BG_IMG,
        ]);
    }
}

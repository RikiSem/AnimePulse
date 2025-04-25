<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\ReviewRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Http\Classes\Season;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexController extends Controller
{

    public const SEASON_ANIME_PAGE_LIMIT = 7;

    public function show(Request $request)
    {
        $pages = Pages::$pages;
        $responseData = AnimeRep::getSeasonAnime()
            ->chunk(self::SEASON_ANIME_PAGE_LIMIT)
            ->all();
        $seasonAnimelist = [];
        foreach ($responseData as $chunk) {
            $pageArray = [];
            foreach ($chunk as $anime) {
                $pageArray[] = ResponseBodyBuilder::anime($anime);
            }
            $seasonAnimelist[] = $pageArray;
        }
        $responseData = ReviewRep::getForLastDays(3);
        $newReviews = [];
        foreach ($responseData as $review) {
            $newReviews[] = ResponseBodyBuilder::review($review);
        }
        $responseData = ReviewRep::getMostPopular();
        $popularReviews = [];
        foreach ($responseData as $review) {
            $popularReviews[] = ResponseBodyBuilder::review($review);
        }

        $newVideos = [
            [
                [
                    'url'=> 'https://www.youtube.com/embed/LFstOa9CzkY?si=Uqbb9ZkCmUBp8KNx',
                    'title' => 'Название видоса',
                    'preview' => 'ссылка на превьюшку',
                ],
                [
                    'url'=> 'https://www.youtube.com/embed/LFstOa9CzkY?si=Uqbb9ZkCmUBp8KNx',
                    'title' => 'JzxfcsdfWgWESdvWERGvSDvfseFwsedНазвание видоса',
                    'preview' => 'ссылка на превьюшку',
                ],
                [
                    'url'=> 'https://www.youtube.com/embed/LFstOa9CzkY?si=Uqbb9ZkCmUBp8KNx',
                    'title' => 'Название видоса',
                    'preview' => 'ссылка на превьюшку',
                ]
            ],
            [
                [
                    'url'=> 'https://www.youtube.com/embed/LFstOa9CzkY?si=Uqbb9ZkCmUBp8KNx',
                    'title' => 'Название видоса',
                    'preview' => 'ссылка на превьюшку',
                ],
                [
                    'url'=> 'https://www.youtube.com/embed/LFstOa9CzkY?si=Uqbb9ZkCmUBp8KNx',
                    'title' => 'Название видоса',
                    'preview' => 'ссылка на превьюшку',
                ],
                [
                    'url'=> 'https://www.youtube.com/embed/LFstOa9CzkY?si=Uqbb9ZkCmUBp8KNx',
                    'title' => 'Название видоса',
                    'preview' => 'ссылка на превьюшку',
                ]
            ]
        ];
        $currentSeason = Season::getCurrentSeason();
        return Inertia::render('MainPage', [
            'pages' => $pages,
            'seasonAnimeList' => $seasonAnimelist,
            'newReviews' => $newReviews,
            'popularReviews' => $popularReviews,
            'newVideos' => $newVideos,
            'currentSeason' => $currentSeason,
            'currentMonth' => Carbon::now()->month,
            'user' => \session('user') ?? [],
        ]);
    }
}

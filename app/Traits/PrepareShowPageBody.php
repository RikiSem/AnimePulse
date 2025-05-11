<?php

namespace App\Traits;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\StudioRep;
use App\Http\Classes\Texts;
use App\Models\Anime;
use Illuminate\Support\Facades\Auth;

trait PrepareShowPageBody
{
    use PrepareFilterList;
    public static function prepareForFilter(AnimeRep $animeRep, StudioRep $studioRep): array
    {
        return [
            'pages' => Pages::$pages,
            'animeStatuses' => self::prepareList(Anime::ANIME_STATUSES, Texts::FILTER_ANIME_STATUS),
            'statusForUser' => self::prepareList(Anime::ANIME_STATUS_FOR_USER, Texts::FILTER_USER_STATUS),
            'animeTypes' => self::prepareList(Anime::ANIME_TYPES_FILTER, Texts::FILTER_ANIME_TYPE),
            'userId' => Auth::getUser() != null ? Auth::getUser()->getAuthIdentifier() : 0,
            'params' => $request->params ?? [],
            'animeReleaseYears' => self::prepareList($animeRep->getAllUnicReleaseYears()->toArray(), Texts::FILTER_RELEASE_YEAR),
            'studios' => self::prepareList($studioRep->getAllStudioNames()->toArray(), Texts::FILTER_STUDIO),
        ];
    }
}

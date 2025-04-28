<?php

namespace App\Traits;

use App\Http\Classes\Season;
use App\Models\Anime;
use Symfony\Component\HttpFoundation\File\File;

trait animeBodyBuild
{
    public static function prerareBody(Anime $anime, $responseData): Anime
    {
        $anime->name = $responseData->russian;
        $anime->external_id = $responseData->id;
        $anime->alter_names = json_encode([
            $responseData->name,
            $responseData->english,
            $responseData->japanese,
            $responseData->synonyms,
        ]);
        $anime->description = $responseData->description;

        $tags = [];
        foreach ($responseData->genres as $tag) {
            $tags[] = $tag->russian;
        }
        $anime->tags = implode(' ', $tags);

        $anime->release_date = $responseData->aired_on;
        if (!is_null($responseData->aired_on)) {
            $anime->release_day = (int) explode('-', $responseData->aired_on)[2] ?? 0;
            $anime->release_month = (int) explode('-', $responseData->aired_on)[1] ?? 0;
            $anime->release_year = (int) explode('-', $responseData->aired_on)[0] ?? 0;
            $anime->in_current_season = Season::animeInCurrentSeason($anime->release_month, $anime->release_year);
            $anime->season = sprintf(
                '%s-%s',
                Season::getSeasonByMonthNumber((int) $anime->release_month),
                explode('-', $responseData->aired_on)[0]
            );
        }

        $studios = [];
        foreach ($responseData->studios as $studio) {
            $studios[] = $studio->name;
        }
        $anime->studio = json_encode($studios);

        $anime->type = $responseData->kind;
        $anime->status = $responseData->status;
        $anime->count_series = $responseData->episodes;

        $posterRequest = 'https://shikimori.one' . $responseData->image->original;
        if (explode('/', $responseData->image->original)[3] === 'missing_original.jpg') {
            if (isset($responseData->videos[0]->image_url)) {
                $posterRequest = $responseData->videos[0]->image_url;
            }
        }
        $poster = new File($posterRequest, false);
        $fileName = explode('?', $anime->external_id.'_'.$poster->getFilename())[0];
        file_put_contents(storage_path('app/public/imgs/posters/') . $fileName, file_get_contents($posterRequest));
        $anime->poster = $fileName;

        $anime->other_rates = $responseData->score;

        $anime->link_to_watch = '-';

        return $anime;
    }
}

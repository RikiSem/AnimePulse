<?php

namespace App\Traits;

use App\Http\Classes\Reps\TagRep;
use App\Http\Classes\Season;
use App\Http\Classes\Storage as CustomStorage;
use App\Models\Anime;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
trait AnimeBodyBuild
{
    use ClearDescription;
    public static function prepareBody(Anime $anime, array $responseData, TagRep $tagRep): Anime
    {
        $anime->name = isset($responseData['russian']) ? $responseData['russian']: $responseData['japanese'];
        $anime->external_id = $responseData['id'];
        $anime->alter_names = [
            $responseData['english'] ?? '',
            $responseData['japanese'] ?? '',
            $responseData['synonyms'] ?? '',
        ];
        $anime->description = !empty($responseData['description']) ? self::clearDescription($responseData['description']) : $responseData['description'];

        $anime->tags = self::prepareTags($responseData['genres'], $tagRep);

        $anime->release_date = null;
        $anime->release_day = isset($responseData['aired_on']['day']) ? (int)$responseData['aired_on']['day'] : null;
        $anime->release_month = isset($responseData['aired_on']['month']) ? (int)$responseData['aired_on']['month'] : null;
        $anime->release_year = isset($responseData['aired_on']['year']) ? (int)$responseData['aired_on']['year'] : null;
        if ($anime->release_year && $anime->release_month && $anime->release_day) {
            $anime->release_date = Carbon::parse(sprintf(
                '%s.%s.%s',
                $responseData['aired_on']['day'],
                $responseData['aired_on']['month'],
                $responseData['aired_on']['year']
            ))->format('d.m.Y');
            $anime->season = sprintf(
                '%s-%s',
                Season::getSeasonByMonthNumber((int) $anime->release_month),
                $responseData['aired_on']['year']
            );
            $anime->in_current_season = Season::animeInCurrentSeason($anime->release_month, $anime->release_year);
        }

        $anime->studio = self::prepareStudios($responseData['studios'] ?? []);

        $anime->type = $responseData['kind'];
        $anime->status = match($responseData['status']) {
            'FINISHED' => Anime::SYSTEM_ANIME_STATUS_DONE,
            'RELEASING' => Anime::SYSTEM_ANIME_STATUS_ONGOING,
            'NOT_YET_RELEASED' => Anime::SYSTEM_ANIME_STATUS_ANNOUNCEMENT,
            'CANCELLED' => Anime::SYSTEM_ANIME_STATUS_CANCELED,
            'HIATUS' => Anime::SYSTEM_ANIME_STATUS_STOPPED
        };
        $anime->count_series = $responseData['episodes'] ?? 0;

        $anime = self::preparePoster($responseData['poster'], $anime);

        //в сервисе из которого берется рейтинг используется 100 бальная система,
        //а тут 10ти бальная,
        //поэтому делим на 10 без знаков после запятой
        $anime->other_rates = (int) number_format($responseData['score'] / 10);

        $anime->link_to_watch = null;

        return $anime;
    }

    private static function prepareStudios(array $items): array {
        return (array_map(function ($item) {
            return $item['name'] ?? $item['russian'];
        }, $items));
    }

    private static function prepareTags(array $tags, TagRep $tagRep): array {
        return array_values(array_filter(array_map(function ($tag) use ($tagRep) {
            return isset($tag['russian']) && $tagRep->tagExist($tag['russian']) ? $tag['russian'] : null;
        }, $tags)));
    }


    private static function preparePoster(string $poster, Anime $anime): Anime {
        $posterRequest = $poster;
        if (!empty($posterRequest) &&  basename($posterRequest) !== 'default.jpg') {
            try {
                $fileResponse = Http::timeout(10)->get($posterRequest);
                if ($fileResponse->successful()) {
                    $fileName = basename(parse_url($posterRequest, PHP_URL_PATH));
                    file_put_contents(storage_path('app/public/imgs/posters/') . $fileName, $fileResponse->body());
                    $anime->poster = $fileName;
                }
            } catch (Exception $e) {
                $anime->poster = CustomStorage::DEFAULT_ANIME_POSTER;
            }
        }

        return $anime;
    }
}

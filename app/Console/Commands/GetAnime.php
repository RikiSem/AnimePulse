<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Season;
use App\Models\Anime;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use function Laravel\Prompts\error;

class GetAnime extends Command
{
    public const MAX_PAGE = 100000;
    public const MIN_PAGE = 1;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-anime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Client $client)
    {
        $this->info('start');
        for($page = self::MIN_PAGE; $page <= self::MAX_PAGE; $page ++) {
            $this->info('get page ' . $page);
            $request = sprintf('https://shikimori.one/api/animes?limit=50&order=id&page=%s', $page);
            $responseData = json_decode($client->get($request,['verify' => false])->getBody());
            if (!empty($responseData)) {
                foreach ($responseData as $animeData) {
                    if (AnimeRep::isAnimeExistByExternalId($animeData->id)) {
                        continue;
                    }
                    sleep(1);
                    $this->info(sprintf('get anime %s from page %s', $animeData->id , $page));
                    $request = sprintf('https://shikimori.one/api/animes/%s', $animeData->id);
                    $responseData = $client->get($request,['verify' => false]);
                    if ($responseData->getStatusCode() == 404) {
                        continue;
                    }
                    $responseData = json_decode($responseData->getBody());
                    $anime = new Anime();
                    $anime->external_id = $responseData->id;
                    $anime->name = $responseData->russian;
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
                    $poster = new File($posterRequest, false);
                    $fileName = explode('?', $poster->getFilename())[0];
                    file_put_contents(storage_path('app/public/imgs/posters/') . $fileName, file_get_contents($posterRequest));
                    $anime->poster = $fileName;

                    $anime->other_rates = $responseData->score;

                    $anime->link_to_watch = '-';
                    $anime->save();
                }
            }
        }
    }
}

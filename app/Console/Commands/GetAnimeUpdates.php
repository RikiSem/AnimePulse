<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Season;
use App\Models\Anime;
use App\Traits\animeBodyBuild;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\File;

class GetAnimeUpdates extends Command
{

    use animeBodyBuild;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-anime-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public const PAGE_LIMIT = 10;

    /**
     * Execute the console command.
     */
    public function handle(Client $client)
    {
        Log::info('start');
        for($page = GetAnime::MIN_PAGE; $page <= self::PAGE_LIMIT ; $page ++) {
            $message = 'get page ' . $page;
            $this->info($message);
            //Log::info($message);
            $request = sprintf('https://shikimori.one/api/animes?limit=50&order=id_desc&page=%s', $page);
            $responseData = json_decode($client->get($request,['verify' => false])->getBody());
            if (!empty($responseData)) {
                foreach ($responseData as $animeData) {
                    sleep(1);
                    $message = sprintf('get anime %s from page %s', $animeData->id , $page);
                    $this->info($message);
                    //Log::info($message);
                    $request = sprintf('https://shikimori.one/api/animes/%s', $animeData->id);
                    $responseData = $client->get($request,['verify' => false]);

                    if ($responseData->getStatusCode() == 404) {
                        continue;
                    }
                    $responseData = json_decode($responseData->getBody());
                    if (is_null($responseData->aired_on) || (int) explode('-', $responseData->aired_on)[0] === Carbon::now()->year) {
                        if (AnimeRep::isAnimeExistByExternalId($animeData->id)) {
                            $message = sprintf('updating anime %s', $animeData->id);
                            $this->info($message);
                            //Log::info($message);
                            $this->updateAnime(AnimeRep::getAnimeByExternalId($animeData->id)->first(), $responseData);
                            continue 2;
                        }
                        $message = sprintf('creating anime %s', $animeData->id);
                        $this->createNewAnime(new Anime(), $responseData);
                    }
                    $this->info($message);
                    //Log::info($message);
                }
            }
        }
        Log::info('end');
    }

    public function updateAnime(Anime $anime, $responseData)
    {
        $anime = animeBodyBuild::prerareBody($anime, $responseData);
        $anime->update();
    }

    public function createNewAnime(Anime $anime, $responseData)
    {
        $anime = animeBodyBuild::prerareBody($anime, $responseData);
        $anime->save();
    }
}

<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\TagRep;
use App\Models\Anime;
use App\Traits\AnimeBodyBuild;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetAnimeUpdates extends Command
{

    use AnimeBodyBuild;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-anime-updates {pagesLimit} {isDesc}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public const ANILIST_URL = 'https://graphql.anilist.co';
    protected const SHIKIMORY_URL = 'https://shikimori.one/api/graphql';
    protected const PER_PAGE = 50;
    public const TIMEOUT = 60;
    protected const DIALOG_YES = 'yes';
    protected const DIALOG_NO = 'no';
    protected const IS_DESC = [
        self::DIALOG_YES,
        self::DIALOG_NO,
    ];

    protected Client $client;
    protected AnimeRep $animeRep;
    protected TagRep $tagRep;
    protected string $isDesc;
    protected int $pageLimit;

    public function __construct(Client $client, AnimeRep $animeRep, TagRep $tagRep)
    {
        parent::__construct();
        $this->client = $client;
        $this->animeRep = $animeRep;
        $this->tagRep = $tagRep;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->isDesc = in_array(
            $this->argument('isDesc'), 
            self::IS_DESC
            ) ? $this->argument('isDesc') : self::DIALOG_NO;
        $this->pageLimit = (int)$this->argument('pagesLimit');

        Log::info('start');
        $page = GetAnime::MIN_PAGE;
        while (true) {
            $message = 'get page ' . $page;
            $this->info($message);
            $responseDataAnilist = $this->getAnimePage($page);
            if (!empty($responseDataAnilist)) {
                foreach ($responseDataAnilist['Page']['media'] as $animeData) {
                    try {
                        $externalId = $animeData['id'];
                        $message = 'start check anime ' . $animeData['id'];
                        $this->info($message);
                        $animeCollection = $this->animeRep->getAnimeByExternalId($externalId);
                        $anime = $animeCollection->first();
                        $isExist = $anime !== null;
                        if (
                            $animeData['title']['native'] === null 
                            || ($anime !== null && Carbon::parse($anime->updated_at)->isToday())
                        ) {
                            continue;
                        }
                        $responseDataShiki = $this->getShikimoriDataByAnimeName($animeData['title']['native']);
                        if (!$responseDataShiki) {
                            continue;
                        }
                        if (!empty($responseDataShiki)) {
                            $russianName = $responseDataShiki['russian'];
                            $russianDescription = $responseDataShiki['description'];
                            $message = 'get anime ' . $externalId;
                            $this->info($message);
                            $data =                                 [
                                'id' => $animeData['id'],
                                'russian' => $russianName,
                                'english' => $animeData['title']['english'],
                                'japanese' => $animeData['title']['native'],
                                'synonyms' => $animeData['title']['romaji'],
                                'description' => $russianDescription,
                                'genres' => $responseDataShiki['genres'],
                                'aired_on' => $animeData['startDate'],
                                'studios' => $animeData['studios']['nodes'],
                                'kind' => $animeData['format'],
                                'status' => $animeData['status'],
                                'episodes' => $animeData['episodes'],
                                'poster' => $animeData['coverImage']['large'],
                                'score' => $animeData['meanScore'],
                            ];
                            if ($isExist) {
                                $message = 'update anime ' . $externalId;
                                $this->info($message);
                                $this->updateAnime(
                                    $anime,
                                    $data
                                );
                                continue;
                            }
                            $message = 'creating anime ' . $externalId;
                            $this->info($message);
                            $this->createNewAnime(
                                new Anime(),
                                $data
                            );
                        }
                    } catch (Exception $e) {
                        Log::error('Ошибка обработки аниме ID ' . $animeData['id'] . ': ' . $e->getMessage());
                        $this->info('Ошибка: ' . $e->getMessage());
                        continue;
                    }
                }
            }
            if (!$responseDataAnilist['Page']['pageInfo']['hasNextPage']) {
                break;
            }

            if ($this->pageLimit > 0) {
                if ($page >= $this->pageLimit) {
                    break;
                }
            }

            $page++;
        }
        Log::info('end');
    }

    public function getAnimePage(int $page): array
    {

        /*$rateLimiterKey = 'anilist-api-rate';
        while (RateLimiter::tooManyAttempts($rateLimiterKey, 90)) {
            $wait = RateLimiter::availableIn($rateLimiterKey);
            $this->info("Anilist API rate limit exceeded. Waiting for $wait seconds.");
            sleep($wait);
        }
        RateLimiter::hit($rateLimiterKey, 60);*/

        $query = sprintf('query ($page: Int, $perPage: Int) {
          Page(page: $page, perPage: $perPage) {
            pageInfo {
                total
                currentPage
                lastPage
                hasNextPage
                perPage
            }
            media(type: ANIME, sort: %s) {
                id
                meanScore
                format
                title {
                    romaji
                    english
                    native
                }
                coverImage {
                    large
                }
                status
                episodes
                studios {
                    nodes {
                        name
                    }
                }   
                startDate {
                    year
                    month
                    day
                }
            }
          }
        }', $this->isDesc === self::DIALOG_YES ? 'ID_DESC' : 'ID');

        $variables = [
            'page' => $page,
            'perPage' => self::PER_PAGE,
        ];

        try {
            $response = $this->client->post(self::ANILIST_URL, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ]
            ]);
            return json_decode($response->getBody(), true)['data'];
        } catch (Exception $e) {
            $this->info(sprintf('retry in %s sec', self::TIMEOUT));
            sleep(self::TIMEOUT);
        }
        return $this->getAnimePage($page);
    }

    public function getShikimoriDataByAnimeName(string $name): array|bool
    {
        /*$rateLimiterKey = 'shikimori-api-rate';
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 30)) {
            $wait = RateLimiter::availableIn($rateLimiterKey);
            $this->info("Shikimori API rate limit exceeded. Wait $wait seconds.");
            sleep($wait);
        }
        RateLimiter::hit($rateLimiterKey, 60);*/

        $query = '
        query ($name: String) {
            animes(search: $name, limit: 1,) {
                id
                malId
                name
                russian
                licenseNameRu
                english
                japanese
                synonyms
                description
                genres {
                    russian
                }
            }
        }';

        $variables = [
            'name' => $name
        ];

        try {
            $response = $this->client->post(self::SHIKIMORY_URL, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return !empty($data['data']['animes']) ? reset($data['data']['animes']) : false;            
        } catch (Exception $e) {
            $this->info(sprintf('retry in %s sec', self::TIMEOUT));
            sleep(self::TIMEOUT);
        }
        return $this->getShikimoriDataByAnimeName($name);
    }

    public function updateAnime(Anime $anime, $responseData)
    {
        try {
            $anime = self::prepareBody($anime, $responseData, $this->tagRep);
            $anime->update();
        } catch (Exception $e) {
            Log::error('Ошибка при обновлении аниме: ' . $e->getMessage(), [
                'anime_id' => $anime->id ?? null,
                'data' => $responseData,
            ]);
            $this->info($e->getMessage());
        }
    }

    public function createNewAnime(Anime $anime, $responseData)
    {
        try {
            $anime = self::prepareBody($anime, $responseData, $this->tagRep);
            $anime->save();
        } catch (Exception $e) {
            Log::error('Ошибка при добавлении аниме: ' . $e->getMessage(), [
                'anime_id' => $anime->id ?? null,
                'data' => $responseData,
            ]);
            $this->info($e->getMessage());
        }
    }
}

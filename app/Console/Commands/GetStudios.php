<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\StudioRep;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetStudios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-studios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Полуает список всех студий';

    protected Client $client;
    protected StudioRep $studioRep;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        $this->studioRep = new StudioRep();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');
        for ($page = 1;$page > 0; $page++) {
            $studioPage = $this->getStudio($page);
            foreach ($studioPage['Page']['studios'] as $studioData) {
                if ($this->studioRep->getByName($studioData['name']) === null) {
                    $this->info(sprintf('saving studio %s', $studioData['id']));
                    $this->studioRep->add(
                        $studioData['name'],
                        null,
                        $studioData['isAnimationStudio']
                    );
                }
            }

            if (!$studioPage['Page']['pageInfo']['hasNextPage']) {
                break;
            }
        }
        $this->info('end');
    }

    public function getStudio(int $page): array 
    {
        $query = 'query ($page: Int) {
            Page(page: $page, perPage: 50) {
                pageInfo {
                  total
                  currentPage
                  lastPage
                  hasNextPage
                  perPage
                }
                studios {
                    id
                    name
                    isAnimationStudio
                    siteUrl
                }
              }
        }';
    
        $variables = [
            'page' => $page,
        ];
        try {
            $response = $this->client->post(GetAnimeUpdates::ANILIST_URL, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ]
            ]);
            return json_decode($response->getBody(), true)['data'];
        } catch (Exception $e) {
            $this->info($e->getMessage());
            $this->info(sprintf('retry in %s sec', GetAnimeUpdates::TIMEOUT));
            sleep(GetAnimeUpdates::TIMEOUT);
        }

        return $this->getStudio($page);
    }
}

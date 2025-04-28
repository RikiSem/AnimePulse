<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\AnimeRep;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class RewriteDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rewrite-description {startFromId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Рерайтит описание тайтлов';

    private string $url = 'https://rewriter-paraphraser-text-changer-multi-language.p.rapidapi.com/rewrite';

    /**
     * Execute the console command.
     */
    public function handle(Client $client, AnimeRep $animeRep)
    {
        foreach ($animeRep->getAllWithoutLimits() as $index => $anime) {
            if (is_null($anime->description) || $anime->id < $this->argument('startFromId')) {
                continue;
            }
            sleep(1);
            $this->info(sprintf('get rewrite for anime %s', $anime->id));
            $body = [
                'language' => 'ru',
                'strength' => 3,
                'text' => $anime->description,
            ];
            $response = $client->request('post', $this->url, [
                'verify' => false,
                'body' => json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-rapidapi-host' => 'rewriter-paraphraser-text-changer-multi-language.p.rapidapi.com',
                    'x-rapidapi-key' => 'fb9bffc074mshecad9a766fff844p185924jsn68350931dc96',
                ],
            ]);
            $anime->description = json_decode($response->getBody())->rewrite;
            $anime->update();
        }
        $this->info('finish');
    }
}

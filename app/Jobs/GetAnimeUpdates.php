<?php

namespace App\Jobs;

use App\Console\Commands\GetAnime;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Season;
use App\Models\Anime;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Laravel\Reverb\Loggers\Log;
use Symfony\Component\HttpFoundation\File\File;

class GetAnimeUpdates implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(Client $client): void
    {
        (new \App\Console\Commands\GetAnimeUpdates())->handle($client);
    }
}

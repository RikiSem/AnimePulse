<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\StudioRep;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class RefactorStudiosInAnimeData extends Command
{
    use \App\Traits\RefactorStudiosInAnimeData;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refactor-studios-in-anime-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    protected AnimeRep $animeRep;
    protected StudioRep $studioRep;
    public function __construct(AnimeRep $animeRep, StudioRep $studioRep)
    {
        parent::__construct();
        $this->animeRep = $animeRep;
        $this->studioRep = $studioRep;
    }
    public function handle()
    {
        Log::info('start');
        $this->info('start');
        $this->animeRep->getAllWithoutLimits()->chunk(50)->each(function ($animeChunk) {
            $animeChunk->each(function ($anime) {
                $this->info(sprintf('starting anime %s', $anime->id));
                $anime->studio = self::refactorStudio($anime->studio, $this->studioRep);
                $anime->update();
            });
        });
        $this->info('end');
        Log::info('end');
    }
}

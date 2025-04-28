<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Season;
use App\Models\Anime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SelectAnimeOnCurrentSeason extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:select-anime-on-current-season';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start');
        Anime::where('release_year', '=', Carbon::now()->year)->chunk(50, function (Collection $animes) {
            foreach ($animes as $anime) {
                try {
                    $anime->in_current_season = Season::isAnimeInCurrentSeason($anime->release_month, $anime->release_year);
                    $anime->update();
                    $this->info(sprintf('аниме %s, год релиза - %s, месяц релиза - %s, в текущем сезоне - %s', $anime->id, $anime->release_year, $anime->release_month, $anime->in_current_season));
                } catch (\Exception $e) {
                    continue;
                }
            }
        });
        $this->info('end');
    }
}

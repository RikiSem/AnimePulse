<?php

namespace App\Console\Commands;

use App\Http\Classes\Reps\AnimeRep;
use App\Models\Anime;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ClearDescription extends Command
{
    use \App\Traits\ClearDescription;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-description {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очищает описания аниме от спецсимволов';

    /**
     * Execute the console command.
     */
    public function handle(AnimeRep $animeRep)
    {
        $this->info('start');
        $id = $this->argument('id'); 
        if ((int)$id === 0) {
            $animeRep->getAllWithoutLimits()->chunk(50)->each(function (Collection $animes) {
                $animes->each(function (Anime $anime) {
                    $this->info(sprintf('anime %s', $anime->id));
                    if (!empty($anime->description)) {
                        $anime->description = self::clearDescription($anime->description);
                        $anime->update();
                    }
                });
            });
        } else {
            $anime = $animeRep->getOne($id);
            if (empty($anime->description)) {
                $this->info('Пустое описание');
            } else {
                $anime->description = self::clearDescription($anime->description);
                $anime->update();
            }
        }

        $this->info('end');
    }
}

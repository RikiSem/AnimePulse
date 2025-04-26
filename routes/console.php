<?php

use App\Console\Commands\SelectAnimeOnCurrentSeason;
use App\Jobs\GetAnimeUpdates;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//Schedule::job(new GetAnimeUpdates(), 'animeUpdatesQueue')->daily();

Schedule::command('app:get-anime-updates')->dailyAt('00:30')->runInBackground();

Schedule::command('app:select-anime-on-current-season')->weekly()->runInBackground();

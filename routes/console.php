<?php

use App\Console\Commands\SelectAnimeOnCurrentSeason;
use App\Jobs\GetAnimeUpdates;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:get-anime-updates 100 yes')->dailyAt('00:30')->runInBackground();
Schedule::command('app:get-anime-updates 1 yes')->dailyAt('06:30')->runInBackground();
Schedule::command('app:get-anime-updates 1 yes')->dailyAt('09:30')->runInBackground();
Schedule::command('app:get-anime-updates 1 yes')->dailyAt('12:30')->runInBackground();
Schedule::command('app:get-anime-updates 1 yes')->dailyAt('15:30')->runInBackground();
Schedule::command('app:get-anime-updates 1 yes')->dailyAt('18:30')->runInBackground();
Schedule::command('app:get-anime-updates 1 yes')->dailyAt('21:30')->runInBackground();

Schedule::command('app:select-anime-on-current-season')->weekly()->runInBackground();

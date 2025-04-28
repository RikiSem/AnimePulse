<?php


namespace App\Http\Classes;


use Carbon\Carbon;

class Season
{
    const WINTER_SEASON = 'Зима';
    const SUMMER_SEASON = 'Лето';
    const SPRING_SEASON = 'Весна';
    const AUTUMN_SEASON = 'Осень';

    const SYSTEM_WINTER_SEASON = 'winter';
    const SYSTEM_SUMMER_SEASON = 'summer';
    const SYSTEM_SPRING_SEASON = 'spring';
    const SYSTEM_AUTUMN_SEASON = 'autumn';

    const SEASONS = [
        self::SYSTEM_WINTER_SEASON => self::WINTER_SEASON,
        self::SYSTEM_SUMMER_SEASON => self::SUMMER_SEASON,
        self::SYSTEM_SPRING_SEASON => self::SPRING_SEASON,
        self::SYSTEM_AUTUMN_SEASON => self::AUTUMN_SEASON
    ];

    public static function getSeason(string $name) {
        return self::SEASONS[$name] ?? null;
    }

    public static function animeInCurrentSeason(int $releaseMonth, int $releaseYear): bool
    {
        return $releaseMonth == Carbon::now()->month && $releaseYear == Carbon::now()->year;
    }

    public static function getCurrentSeason()
    {
        $result = '';
        $currentMonth = Carbon::now()->month;
        if (1 <= $currentMonth && $currentMonth <= 2 || $currentMonth === 12) {
            $result = sprintf('%s %s', self::WINTER_SEASON, Carbon::now()->year);
        } else if (3 <= $currentMonth && $currentMonth <= 5) {
            $result = sprintf('%s %s', self::SPRING_SEASON, Carbon::now()->year);
        } else if (6 <= $currentMonth && $currentMonth <= 8) {
            $result = sprintf('%s %s', self::SUMMER_SEASON, Carbon::now()->year);
        } else if (9 <= $currentMonth && $currentMonth <= 11) {
            $result = sprintf('%s %s', self::AUTUMN_SEASON, Carbon::now()->year);
        }

        return $result;
    }

    public static function getSystemCurrentSeason()
    {
        $result = '';
        $currentMonth = Carbon::now()->month;
        if (1 <= $currentMonth && $currentMonth <= 2 || $currentMonth === 12) {
            $result = sprintf('%s_%s', self::SYSTEM_WINTER_SEASON, Carbon::now()->year);
        } else if (3 <= $currentMonth && $currentMonth <= 5) {
            $result = sprintf('%s_%s', self::SYSTEM_SPRING_SEASON, Carbon::now()->year);
        } else if (6 <= $currentMonth && $currentMonth <= 8) {
            $result = sprintf('%s_%s', self::SYSTEM_SUMMER_SEASON, Carbon::now()->year);
        } else if (9 <= $currentMonth && $currentMonth <= 11) {
            $result = sprintf('%s_%s', self::SYSTEM_AUTUMN_SEASON, Carbon::now()->year);
        }

        return $result;
    }

    public static function getSeasonByMonthNumber(int $num) {
        switch ($num) {
            case 1:
            case 2:
            case 12:
                $result = self::SYSTEM_WINTER_SEASON;
                break;
            case 3:
            case 4:
            case 5:
                $result = self::SYSTEM_SPRING_SEASON;
                break;
            case 6:
            case 7:
            case 8:
                $result = self::SYSTEM_SUMMER_SEASON;
                break;
            case 9:
            case 10:
            case 11:
                $result = self::SYSTEM_AUTUMN_SEASON;
                break;
        }
        return $result;
    }

    public static function isAnimeInCurrentSeason(int $animeReleaseMonth, int $animeReleaseYear): bool
    {
        $currentSeason = self::getSeasonByMonthNumber(Carbon::now()->month);
        $animeReleaseMonth = self::getSeasonByMonthNumber($animeReleaseMonth);
        return $animeReleaseMonth === $currentSeason && $animeReleaseYear === Carbon::now()->year;
    }
}

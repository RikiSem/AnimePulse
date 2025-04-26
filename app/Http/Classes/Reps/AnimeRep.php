<?php


namespace App\Http\Classes\Reps;


use App\Models\Anime;
use App\Models\Favorites;
use App\Models\UserViews;
use Illuminate\Database\Eloquent\Collection;

class AnimeRep
{
    public const ANIME_LIMIT_ON_PAGE = 30;

    public function getAnimeByExternalId(int $id): Collection
    {
        return Anime::where('external_id', '=', $id)
            ->get();
    }

    public function isAnimeExistByExternalId(int $id): bool
    {
        return !$this->getAnimeByExternalId($id)->isEmpty();
    }

    public function getAnimeCount(): int
    {
        return Anime::all()->count();
    }

    public function getOne(int $id): Anime
    {
        return Anime::find($id);
    }

    public function getSeasonAnime(): Collection
    {
        return Anime::where('in_current_season', '=', 1)
            ->get();
    }

    public function getAllWithoutLimits(): Collection
    {
        return Anime::all();
    }

    public function getAll(int $offset = 0, $filters = []): Collection
    {
        $result = Anime::limit(self::ANIME_LIMIT_ON_PAGE)
            ->offset($offset * self::ANIME_LIMIT_ON_PAGE);
        if ($filters != []) {
            foreach ($filters as $param => $value) {
                switch ($param) {
                    case 'offset':
                        break;
                    case 'view_status':
                    case 'userId':
                        if ($value > 0 && $filters['view_status']){
                            $result->leftJoin('user_views', 'user_views.anime_id', '=', 'animes.id')
                                ->where('user_views.user_id', '=', $filters['userId'])
                                ->where('user_views.view_status', '=', $filters['view_status']);
                        }
                        break;
                    case 'tags':
                        foreach ($value as $tag) {
                            $result->where('tags', 'like', '%'. $tag .'%');
                        }
                        break;
                    case 'favoriteStatus':
                        if ($value) {
                            $result->leftJoin('favorites', 'favorites.entity_id', '=', 'animes.id');
                            $result->where('favorites.entity_type', '=', FavoriteRep::ENTITY_TYPES['anime']);
                        }
                        break;
                    case 'newest':
                        if ($value) {
                            $result->orderBy('id', 'desc');
                        }
                        break;
                    default:
                        $result->where($param, 'like', '%'. $value .'%');
                }
            }
        }
        return $result->get();
    }

    public function getUserAnime(int $userId = 0, array $filters = []): Collection
    {
        $result = UserViews::where('user_views.user_id', '=', $userId);
        if (!empty($filters)) {
            $result->leftJoin('animes', 'animes.id', '=', 'user_views.anime_id');
            foreach ($filters as $param => $value) {
                switch ($param) {
                    case 'userId':
                        break;
                    case 'tags':
                        foreach ($value as $tag) {
                            $result->where('tags', 'like', '%'. $tag .'%');
                        }
                        break;
                    case 'favoriteStatus':
                        if ($value) {
                            $result->leftJoin('favorites', 'favorites.entity_id', '=', 'animes.id');
                            $result->where('favorites.entity_type', '=', FavoriteRep::ENTITY_TYPES['anime']);
                        }
                        break;
                    default:
                        if(!empty($value)) {
                            if (in_array($param, Anime::ANIME_FIELDS)) {
                                $result->where('animes.' . $param, 'like', '%'. $value .'%');
                            } else {
                                $result->where('user_views.' . $param, '=', $value);
                            }
                        }
                }
            }
        }
        return $result->get();
    }
}

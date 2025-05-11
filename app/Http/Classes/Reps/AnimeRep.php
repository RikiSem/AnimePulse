<?php


namespace App\Http\Classes\Reps;

use App\Models\Anime;
use App\Models\Favorites;
use App\Models\UserViews;
use Illuminate\Database\Eloquent\Collection;

class AnimeRep
{
    protected StudioRep $studioRep;
    public function __construct()
    {
        $this->studioRep = new StudioRep();
    }
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
    public function getAll(int $offset = 0, array $filters = []): Collection
    {
        $result = Anime::limit(self::ANIME_LIMIT_ON_PAGE)
            ->offset($offset * self::ANIME_LIMIT_ON_PAGE);

        // Флаги — какие join'ы уже добавлены, чтобы не дублировать
        $joinedUserViews = false;
        $joinedFavorites = false;

        if (!empty($filters)) {
            foreach ($filters as $param => $value) {
                switch ($param) {
                    case 'offset':
                        break;

                    case 'view_status':
                    case 'userId':
                        if (!empty($filters['userId']) && !empty($filters['view_status']) && !$joinedUserViews) {
                            $result->leftJoin('user_views', 'user_views.anime_id', '=', 'animes.id')
                                ->where('user_views.user_id', '=', $filters['userId'])
                                ->where('user_views.view_status', '=', $filters['view_status']);
                            $joinedUserViews = true;
                        }
                        break;

                    case 'tags':
                        foreach ((array) $value as $tag) {
                            $result->where('tags', 'like', '%' . $tag . '%');
                        }
                        break;

                    case 'name':
                        if (!empty($value)) {
                            $result->where(function ($query) use ($value) {
                                $query->where('name', 'like', '%' . $value . '%')
                                    ->orWhere('alter_names', 'like', '%' . $value . '%');
                            });
                        }
                        break;

                    case 'studio':
                        if (!empty($value)) {
                            $studio = $this->studioRep->getByName($value);
                            
                            if ($studio) {
                                $result->join('anime_studios', 'animes.id', '=', 'anime_studios.anime_id')
                                    ->where('anime_studios.studio_id', '=', $studio->id);
                            }
                        }
                        break;

                    case 'type':
                        if (!empty($value)) {
                            $result->where('animes.type', '=', $value);
                        }
                        break;

                    case 'favoriteStatus':
                        if ($value && !$joinedFavorites) {
                            $result->leftJoin('favorites', 'favorites.entity_id', '=', 'animes.id')
                                ->where('favorites.entity_type', '=', FavoriteRep::ENTITY_TYPES['anime']);
                            $joinedFavorites = true;
                        }
                        break;

                    case 'newest':
                        if ($value) {
                            $result->orderBy('release_year', 'desc');
                        }
                        break;

                    default:
                        $result->where("animes.$param", 'like', '%' . $value . '%');
                        break;
                }
            }
        }

        return $result
            ->select('animes.*')
            ->distinct()
            ->get();
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

    public function getAllUnicReleaseYears()
    {
        return Anime::select('release_year')
            ->distinct()
            ->orderBy('release_year', 'desc')
            ->pluck('release_year');
    }
}

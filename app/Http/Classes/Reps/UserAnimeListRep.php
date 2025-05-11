<?php


namespace App\Http\Classes\Reps;


use App\Models\Anime;
use App\Models\UserAnimeList;
use Illuminate\Database\Eloquent\Model;

class UserAnimeListRep
{
    public function getOne(int $userId, int $animeId) {
        /*return UserAnimeList::where('user_id', '=', $userId)
            ->where('anime_id', '=', $animeId)
            ->first();*/
            return UserAnimeList::firstOrCreate([
                'user_id' => $userId,
                'anime_id' => $animeId
            ]);
    }

    public function getUserAnimeList(int $userId, array $filters = []) {
        $result = UserAnimeList::where('user_id', '=', $userId);
        if (!empty($filters)) {
            $result->leftJoin('animes', 'animes.id', '=', 'user_anime_lists.anime_id');
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
                            $result->where('user_anime_lists.favorite', '=', true);
                        }
                        break;
                    default:
                        if(!empty($value)) {
                            if (in_array($param, Anime::ANIME_FIELDS)) {
                                $result->where('animes.' . $param, 'like', '%'. $value .'%');
                            } else {
                                $result->where('user_anime_lists.' . $param, '=', $value);
                            }
                        }
                }
            }
        }
        return $result->get();
    }

    public function createNewList(int $userId, int $animeId, string $status) {
        $animeList = new UserAnimeList();
        $animeList->user_id = $userId;
        $animeList->anime_id = $animeId;
        $animeList->view_status = $status;
        $animeList->save();
        return $animeList;
    }

    public function setAnimeInList(int $userId, int $animeId, string $status) {
        $animeList = UserAnimeList::where('user_id', '=', $userId)
        ->where('anime_id', '=', $animeId)
            ->first();
        if (empty($animeList)) {
            $this->createNewList(
                $userId,
                $animeId,
                $status
            );
        } else {
            $animeList->view_status = $status;
            $animeList->update();
        }
    }

    public function removeFromList(int $userId, int $animeId) {
        $animeList = UserAnimeList::where('user_id', '=', $userId)
            ->where('anime_id', '=', $animeId)
            ->first();
        $animeList->view_status = null;
        $animeList->update();
    }

    public function setFavorite(int $userId, int $animeId) {
        $animeList = UserAnimeList::where('user_id', '=', $userId)
            ->where('anime_id', '=', $animeId)
            ->first();
        if (empty($animeList)) {
            $animeList = new UserAnimeList();
            $animeList->user_id = $userId;
            $animeList->anime_id = $animeId;
            $animeList->view_status = null;
            $animeList->favorite = true;
            $animeList->save();
        } else {
            $animeList->favorite = true;
            $animeList->update();
        }
    }

    public function removeFromFavorite(int $userId, int $animeId): UserAnimeList
    {
        $animeList = UserAnimeList::where('user_id', '=', $userId)
            ->where('anime_id', '=', $animeId)
            ->first();
        $animeList->favorite = false;
        $animeList->update();

        return $animeList;
    }
}

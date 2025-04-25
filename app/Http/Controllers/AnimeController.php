<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Classes\Pages;
use App\Http\Classes\Reps\AnimeRep;
use App\Http\Classes\Reps\FavoriteRep;
use App\Http\Classes\Reps\UserAnimeListRep;
use App\Http\Classes\Reps\UserViewRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Http\Classes\Season;
use App\Http\Classes\Storage;
use App\Http\Classes\Texts;
use App\Models\Anime;
use App\Models\Tag;
use App\Models\UserAnimeList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use function Symfony\Component\Translation\t;

class AnimeController extends Controller
{
    public function addToList(Request $request) {
        $userAnimeList = UserAnimeListRep::getOne($request->user_id, $request->anime_id);
        if (empty($userAnimeList)) {
            $userAnimeList = UserAnimeListRep::createNewList(
                $request->user_id,
                $request->anime_id,
                $request->new_status
            );
        } else {
            if ($userAnimeList->view_status === $request->new_status) {
                $userAnimeList->view_status = null;
            } else {
                $userAnimeList->view_status = $request->new_status;
            }
            $userAnimeList->update();
            if ($userAnimeList->view_status === null) {
                $userAnimeList->delete();
            }
        }
        return response('done');
    }

    public function removeFromList(Request $request) {
        UserAnimeListRep::removeFromList($request->user_id, $request->anime_id);
        NotificationController::sendNotification(
            $request->user_id,
            Texts::EVENT_TEXTS['remove_status']
        );
        return response('done');
    }

    public function showOne(Request $request) {
        return Inertia::render('AnimePage', [
            'id' => $request->id,
            'pages' => Pages::$pages,
            'user_statuses' => array_slice(array_values(Anime::ANIME_STATUS_FOR_USER), 1),
            'rule' => Texts::getRules(),
        ]);
    }

    public function getOne(Request $request) {
        $responseData = AnimeRep::getOne($request->id);
        $result =  ResponseBodyBuilder::anime($responseData, $request->user_id);
        return response($result);
    }

    public function getForUser(Request $request) {
        $responseData = UserAnimeListRep::getUserAnimeList($request->userId, $request->params);
        $result = [];
        foreach ($responseData as $key => $anime) {
            $result[] = ResponseBodyBuilder::userAnimeList($anime, $request->userId);
        }
        return response(!empty($result) ? $result : 'null');
    }

    public function filter(Request $request) {;
        if ($request->userId != 0) {
            $responseData = AnimeRep::getUserAnime($request->userId, [
                'name' => $request->animeName,
                'status' => $request->animeStatus,
                'view_status' => $request->animeStatusForUser,
                //'favorite_status' => $request->favoriteStatus,
            ]);

            $result = [];
            foreach ($responseData as $key => $anime) {
                $result[] = ResponseBodyBuilder::anime($anime);
            }
        } else {
            $responseData = AnimeRep::getAll();
            $result = [];
            foreach ($responseData as $key => $anime) {
                $result[] = ResponseBodyBuilder::anime($anime);
            }

        }

        return response(!empty($result) ? $result : 'null');
    }

    public function tags(Request $request) {
        $responseData = Tag::all();
        $result = [];
        foreach ($responseData as $tag) {
            $result[] = [
                'title' => $tag->russian_name,
                'value' => $tag->name
            ];
        }
        return response($result);
    }
}

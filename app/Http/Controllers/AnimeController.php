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
use App\Models\UserAnimeRate;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use function Symfony\Component\Translation\t;

class AnimeController extends Controller
{
    protected UserAnimeListRep $userAnimeListRep;
    protected AnimeRep $animeRep;
    public function __construct(AnimeRep $animeRep, UserAnimeListRep $userAnimeListRep) {
        $this->userAnimeListRep = $userAnimeListRep;
        $this->animeRep = $animeRep;
    }
    public function addToRate(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'anime_id' => 'required|integer|exists:animes,id',
                'rate' => 'required|integer',
            ]);
            $userRate = UserAnimeRate::firstOrCreate([
                'user_id' => $validated['user_id'],
                'anime_id' => $validated['anime_id']
            ], [
                'user_id' => $validated['user_id'],
                'anime_id' => $validated['anime_id'],
                'user_rate' => $validated['rate']
            ]);

            $userRate->user_rate = $validated['rate'];
            $userRate->update();
            $userRate->anime->update();

            $result = response()->json(['status' => 'done']);
        } catch (Exception $e) {
            $result = response()->json(['error' => $e->getMessage()], 500);
        }

        return $result;
    }
    public function addToList(Request $request) {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'anime_id' => 'required|integer|exists:animes,id',
                'new_status' => 'nullable|string|in:will_watch,watch,watched,dropped,favorite',
            ]);
            $userAnimeList = $this->userAnimeListRep->getOne($validated['user_id'], $validated['anime_id']);
            if (empty($userAnimeList)) {
                $userAnimeList = $this->userAnimeListRep->createNewList(
                    $validated['user_id'],
                    $validated['anime_id'],
                    $validated['new_status']
                );
            } else {
                if ($userAnimeList->view_status === $validated['new_status']) {
                    $userAnimeList->view_status = null;
                } else {
                    $userAnimeList->view_status = $validated['new_status'];
                }
                $userAnimeList->update();
                if ($userAnimeList->view_status === null && !$userAnimeList->favorite) {
                    $userAnimeList->delete();
                }
            }

            $result = response()->json(['status' => 'done']);
        } catch (Exception $e) {
            $result = response()->json(['error' => $e->getMessage()], 500);
        }
        return $result;
    }

    public function removeFromList(Request $request) {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'anime_id' => 'required|integer|exists:animes,id',
            ]);

            $this->userAnimeListRep->removeFromList($validated['user_id'], $validated['anime_id']);
            NotificationController::sendNotification(
                $validated['user_id'],
                Texts::EVENT_TEXTS['remove_status']
            );
            $result = response()->json(['status' => 'done']);
        } catch (Exception $e) {
            $result = response()->json(['error' => $e->getMessage()], 500);
        }

        return $result;
    }

    public function showOne(Request $request) {
        return Inertia::render('AnimePage', [
            'id' => $request->id,
            'pages' => Pages::$pages,
            'user_statuses' => array_values(Anime::ANIME_STATUS_FOR_USER),
            'rule' => Texts::getRules(),
        ]);
    }

    public function getOne(Request $request) {
        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:animes,id',
                'user_id' => 'required|integer',
            ]);

            $responseData = $this->animeRep->getOne($validated['id']);
            $result =  ResponseBodyBuilder::anime($responseData, $validated['user_id']);

            $result = response($result);
        } catch (Exception $e) {
            $result = response()->json(['error' => $e->getMessage()], 500);
        }

        return $result;
    }

    public function getForUser(Request $request) {
        try {
            $validated = $request->validate([
                'params' => 'array',
                'userId' => 'required|integer',
            ]);

            $responseData = $this->userAnimeListRep->getUserAnimeList($validated['userId'], $validated['params']);
            $result = $responseData->map(function(UserAnimeList $anime) use (&$validated) {
                return ResponseBodyBuilder::userAnimeList($anime, $validated['userId']);
            });

            $result = response(!empty($result) ? $result : null);
        } catch (Exception $e) {
            $result = response()->json(['error' => $e->getMessage()], 500);
        }

        return $result;
    }

    public function filter(Request $request) {;
        try {
            $validated = $request->validate([
                'animeName' => 'required|string',
                'userId' => 'required|integer',
                'status' => 'required|string',
                'view_status' => 'required|string',
                'animeStatus' => 'required|string',
                'animeStatusForUser' => 'required|string'
            ]);

            if ($validated['userId'] != 0) {
                $responseData = $this->animeRep->getUserAnime($validated['userId'], [
                    'name' => $validated['animeName'],
                    'status' => $validated['animeStatus'],
                    'view_status' => $validated['animeStatusForUser'],
                ]);
            } else {
                $responseData = $this->animeRep->getAll();
            }

            $result = $responseData->map(function($anime) {
                return ResponseBodyBuilder::anime($anime);
            });

            $result = response(!empty($result) ? $result : null);
        } catch (Exception $e) {
            $result = response()->json(['error' => 'Failed to get animes by filter'], 500);
        }

        return $result;
    }

    public function tags(Request $request) {
        try {
            $responseData = Tag::all();
            $result = $responseData->map(function ($tag) {
                return [
                    'title' => $tag->russian_name,
                    'value' => $tag->name
                ];
            });
            $result = response($result);
        } catch (Exception $e) {
            $result = response()->json(['error' => 'Failed to get anime`s tags'], 500);
        }

        return $result;
    }
}

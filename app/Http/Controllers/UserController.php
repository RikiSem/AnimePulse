<?php

namespace App\Http\Controllers;

use App\Http\Classes\Pages;
use App\Http\Classes\Reps\CommentRep;
use App\Http\Classes\Reps\ReviewRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Models\Anime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show(Request $request) {
        if ((int)$request->id === Auth::user()->id) {
            return redirect()->route('dashboard');
        }
        return Inertia::render('UserPage',
            [
                'pages' => Pages::$pages,
                'user' => $this->getUser($request)->original,
                'animeStatuses' => Anime::ANIME_STATUSES,
                'statusForUser' => Anime::ANIME_STATUS_FOR_USER,
            ]);
    }

    public function showUserReviews(Request $request) {
        $responseData = ReviewRep::getForUser($request->id);
        $reviews = [];
        foreach ($responseData as $review) {
            $reviews[] = ResponseBodyBuilder::review($review);
        }
        return Inertia::render('UserPageReviews', [
            'pages' => Pages::$pages,
            'reviews' => $reviews,
            'user' => $this->getUser($request)->original,
        ]);
    }

    public function showUserComments(Request $request) {
        $responseData = CommentRep::getForUser($request->id);
        $comments = [];
        foreach ($responseData as $comment) {
            $comments[] = ResponseBodyBuilder::comment($comment);
        }
        return Inertia::render('UserPageComments', [
            'pages' => Pages::$pages,
            'comments' => $comments,
            'user' => $this->getUser($request)->original,
        ]);
    }

    public function getUser(Request $request) {
        return response(ResponseBodyBuilder::user(User::find($request->id)));
    }

    public function changeAvatar(Request $request) {
        $user = User::find(Auth::user()->id);
        $newAvatar = $request->file('avatar');
        $newAvatar->storeAs('imgs/avatars', $newAvatar->getClientOriginalName(),['disk' => 'public']);
        $user->avatar = $newAvatar->getClientOriginalName();
        $user->update();
        NotificationController::sendNotification(Auth::user()->id, 'Аватар изменен');
        return response('done');
    }
}

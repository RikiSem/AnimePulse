<?php

namespace App\Http\Controllers;

use App\Http\Classes\Reps\ReactionRep;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function create(Request $request) {

        $userReactionOnReview = ReactionRep::getUserReactionByReviewId($request->id, $request->user_id);
        if (is_null($userReactionOnReview)) {
            ReactionRep::create($request->id, $request->user_id, $request->type);
        } else {
            $userReactionOnReview->delete();
            if ($userReactionOnReview->type !== $request->type) {
                ReactionRep::create($request->id, $request->user_id, $request->type);
            }
        }
        return response('done');
    }
}

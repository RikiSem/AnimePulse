<?php

namespace App\Http\Controllers;

use App\Http\Classes\Reps\CommentRep;
use App\Http\Classes\ResponseBodyBuilder;
use App\Http\Classes\Storage;
use App\Http\Classes\Texts;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getForAnime(Request $request) {
        $responseData = CommentRep::getForAnime($request->id, $request->offset);
        $result = [];
        foreach ($responseData as $key => $comment) {
            if ($comment->reply_to === 0) {
                $result[] = ResponseBodyBuilder::comment($comment);
            }
        }
        return response($result);
    }

    public function create(Request $request) {
        CommentRep::create(
            $request->data['author'],
            $request->data['comment'],
            $request->entity_type,
            $request->entity_id,
            $request->data['reply_to'] ?? 0
        );
        NotificationController::sendNotification(
            $request->data['author'],
            Texts::EVENT_TEXTS['comment_send']
        );
        return response('done');
    }

    public function getForReview(Request $request) {
        $responseData = CommentRep::getForReview($request->id, $request->offset);
        $result = [];
        foreach ($responseData as $key => $comment) {
            $result[] = ResponseBodyBuilder::comment($comment);
        }
        return response($result);
    }

    public function getForUser(Request $request) {
        $responseData = CommentRep::getForUser($request->userId);
        $result = [];
        foreach ($responseData as $key => $comment) {
            $result[] = ResponseBodyBuilder::comment($comment);
        }
        return response($result);
    }
}

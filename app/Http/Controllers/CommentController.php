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
    protected CommentRep $commentRep;
    public function __construct(CommentRep $commentRep) {
        $this->commentRep = $commentRep;
    }
    public function getForAnime(Request $request) {
        $responseData = $this->commentRep->getForAnime($request->id, $request->offset);
        $result = [];
        $result = $responseData->map(function ($comment) {
            return ResponseBodyBuilder::comment($comment);
        });
        return response($result);
    }

    public function create(Request $request) {
        $this->commentRep->create(
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
        $responseData = $this->commentRep->getForReview($request->id, $request->offset);
        $result = [];
        $result = $responseData->map(function ($comment) {
            return ResponseBodyBuilder::comment($comment);
        });
        return response($result);
    }

    public function getForUser(Request $request) {
        $responseData = $this->commentRep->getForUser($request->userId);
        $result = [];
        $result = $responseData->map(function ($comment) {
            return ResponseBodyBuilder::comment($comment);
        });
        return response($result);
    }
}

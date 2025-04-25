<?php


namespace App\Http\Classes\Reps;


use App\Models\Anime;
use App\Models\Comment;
use App\Models\Reviews;
use Illuminate\Database\Eloquent\Collection;

class CommentRep implements BaseRepositoryInterface
{
    public static function getOne(int $id): Comment
    {
        return Comment::find($id);
    }
    public static function getAll(): Collection
    {
        return Comment::all();
    }
    public static function getForAnime(int $id, int $offset = 0) {
        return Comment::where('commentable_id', '=', $id)
            ->where('commentable_type', '=', Anime::ENTITY_TYPE)
            ->limit(Comment::LIMIT_ON_PAGE)
            ->offset($offset * Comment::LIMIT_ON_PAGE)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function getForReview(int $id, int $offset = 0)
    {
        return Comment::where('commentable_id', '=', $id)
            ->where('commentable_type', '=', Reviews::ENTITY_TYPE)
            ->limit(Comment::LIMIT_ON_PAGE)
            ->offset($offset * Comment::LIMIT_ON_PAGE)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function getForUser(int $userId) {
        return Comment::where('user_id', '=', $userId)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function create(int $userId, string $commentText, string $entityType, int $entityId, int $replyTo = 0) {
        $comment = new Comment();
        $comment->user_id = $userId;
        $comment->comment = $commentText;
        $comment->commentable_type = Comment::COMMENTS_ENTITIES_TYPES[$entityType];
        $comment->commentable_id = $entityId;
        $comment->reply_to = $replyTo;
        $comment->save();
    }
}

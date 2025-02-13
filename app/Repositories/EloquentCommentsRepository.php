<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class EloquentCommentsRepository implements CommentsRepository
{

    public function create(array $data): Comment
    {
            return Comment::create($data);
    }

    public function findCommentById(int $commentId): Comment
    {
        return Comment::findOrFail($commentId);
    }

    public function findCommentByIdWithLikes(int $commentId): Comment
    {
        return Comment::withLikesComment()->where('comments.id', $commentId)->first();
    }

    public function getCommentsByPostId(int $postId): Collection
    {
        return Comment::withLikesComment()
            ->whereNull('parent_id')
            ->where('post_id', $postId)
            ->with('user')
            ->get();
    }

    public function getCommentWithLikes($commentId): ?Comment
    {
        return Comment::withLikesComment()->find($commentId);
    }

    public function getCommentWithUserAndWithLikes($commentId): ?Comment
    {
        return Comment::withLikesComment()->with('user')->find($commentId);
    }

    public function getFirstSubComment(int $commentId): ?Comment
    {
        return Comment::withLikesComment()
            ->where('parent_id', $commentId)
            ->with('user')
            ->first();
    }

    public function getMainComments(int $commentId): Collection
    {
        return Comment::withLikesComment()
            ->where('parent_id', $commentId)
            ->with('user')
            ->get();
    }
}

<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentsRepository
{
    public function create(array $data): Comment;
    
    public function findCommentById(int $commentId): Comment;

    public function findCommentByIdWithLikes(int $commentId): Comment;

    public function getCommentsByPostId(int $postId): Collection;

    public function getCommentWithLikes($commentId): ?Comment;

    public function getCommentWithUserAndWithLikes($commentId): ?Comment;

    public function getFirstSubComment(int $commentId): ?Comment;
    
    public function getMainComments(int $commentId): Collection;
   
}

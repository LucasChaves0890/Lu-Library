<?php

namespace App;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait Likable
{
    public function scopeWithLikesComment(Builder $query)
    {
        $query->leftJoinSub(
            'select likeable_id, 
                    COALESCE(sum(case when liked = true then 1 else 0 end), 0) as likes, 
                    COALESCE(sum(case when liked = false then 1 else 0 end), 0) as dislikes 
             from likes 
             where likeable_type = \'App\\Models\\Comment\' 
             group by likeable_id',
            'likes',
            function ($join) {
                $join->on('likes.likeable_id', '=', 'comments.id'); 
            }
        )
            ->addSelect('comments.*', 'likes.likes', 'likes.dislikes');
    }

    public function scopeWithLikesPost(Builder $query)
    {
        $query->leftJoinSub(
            'select likeable_id, 
                    COALESCE(sum(case when liked = true then 1 else 0 end), 0) as likes, 
                    COALESCE(sum(case when liked = false then 1 else 0 end), 0) as dislikes 
             from likes 
             where likeable_type = \'App\\Models\\Post\' 
             group by likeable_id',
            'likes',
            function ($join) {
                $join->on('likes.likeable_id', '=', 'posts.id'); 
            }
        )
            ->addSelect('posts.*', 'likes.likes', 'likes.dislikes');
    }


    public function like($user = null, $liked = true)
    {
        $userId = $user ? $user->id : auth()->id();
        $existingLike = $this->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            if ($existingLike->liked == $liked) {
                $existingLike->delete();
            } else {
                $existingLike->update(['liked' => $liked]);
            }
        } else {
            $this->likes()->create([
                'user_id' => $userId,
                'liked' => $liked,
            ]);
        }
    }

    public function dislike($user = null)
    {
        return $this->like($user, false);
    }

    public function isLikedBy(User $user)
    {
        return (bool) $this->likes()
            ->where('user_id', $user->id)
            ->where('liked', true)
            ->exists();
    }

    public function isDislikedBy(User $user)
    {
        return (bool) $this->likes()
            ->where('user_id', $user->id)
            ->where('liked', false)
            ->exists();
    }
}

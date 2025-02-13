<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EloquentPostRepository implements PostsRepository
{
    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            return Post::create($data);
        });
    }

    public function delete(int $post, int $user): ?bool
    {
        return DB::transaction(function () use ($post, $user) {
            return Post::where('id', $post)->where('user_id', $user)->delete();
        });
    }

    public function findPost(int $postId): Post
    {
        return Post::withLikesPost()
            ->find($postId);
    }

    public function findPostWithUserAndWithLikes($postId): Post
    {
        return Post::withLikesPost()->with('user')->find($postId);
    }

    public function getPostsByUserId(int $userId): Collection
    {
        return Post::withLikesPost()
            ->where('user_id', $userId)
            ->whereHas('book', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function postsFromFollowings($followingIds): Collection
    {
        return  Post::withLikesPost()
            ->whereIn('user_id', $followingIds)
            ->whereHas('book', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with(['user', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function popularPosts($postIdsFromFollowings): Collection
    {
        return Post::withLikesPost()
            ->withCount('likes')
            ->whereNotIn('id', $postIdsFromFollowings)
            ->whereHas('book', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();
    }

    public function findPostWithLikes(int $postId): ?Post
    {
        return Post::withLikesPost()->find($postId);
    }

    public function getPostCommentsCount(Post $post): int
    {
        return $post->comments()->mainComments()->count();
    }
}

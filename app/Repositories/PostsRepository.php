<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostsRepository
{
    public function create(array $data): Post;

    public function delete(int $post, int $user): ?bool ;

    public function findPost(int $postId): Post;

    public function findPostWithUserAndWithLikes($postId): Post;

    public function getPostsByUserId(int $userId): Collection;

    public function postsFromFollowings($followingIds): Collection;

    public function popularPosts($postIdsFromFollowings): Collection;

    public function findPostWithLikes(int $postId): ?Post;

    public function getPostCommentsCount(Post $postId): int;

}

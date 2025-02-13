<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostsRepository;
use Illuminate\Http\Request;

class LikePostsService
{
    public function __construct(
        private PostsRepository $postsRepository
    )
    {}

    public function like(Request $request, Post $post): array
    {
        $post->like($request->user(), true);

        return $this->buildReactionResponse($request, $post->id);
    }

    public function dislike(Request $request, Post $post): array
    {
        $post->dislike($request->user());

        return $this->buildReactionResponse($request, $post->id);
    }

    private function buildReactionResponse($request, int $postId): array
    {
        $post = $this->postsRepository->findPostWithLikes($postId);

        $liked = $post->isLikedBy($request->user());
        $disliked = $post->isDislikedBy($request->user());

        return [
            'likes' => $post->likes,
            'dislikes' => $post->dislikes,
            'liked' => $liked,
            'disliked' => $disliked
        ];
    }
}

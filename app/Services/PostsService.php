<?php

namespace App\Services;

use App\Http\Requests\PostFormRequest;
use App\Models\Post;
use App\Repositories\PostsRepository;
use Illuminate\Http\Request;

class PostsService
{
    public function __construct(
        private dateFormatService $dateFormatter,
        private PostsRepository $repository,
        private LikePostsService $likePostsService
    ) {}

    public function createPost(PostFormRequest $request): Post
    {
        return $this->repository->create($request->all());
    }

    public function delete(int $post, int $user): ?bool 
    {
        return $this->repository->delete($post, $user);
    }

    public function timelinePosts($followingIds, $authUser)
    {
        $postsFromFollowings = $this->repository->postsFromFollowings($followingIds);
        $postIdsFromFollowings = $postsFromFollowings->pluck('id');

        $popularPosts = $this->repository->popularPosts($postIdsFromFollowings);

        $formattedPostsFromFollowings = collect($this->formatPostsCollection($postsFromFollowings, $authUser));
        $formattedPopularPosts = collect($this->formatPostsCollection($popularPosts, $authUser));

        return $formattedPostsFromFollowings->merge($formattedPopularPosts);
    }


    private function formatPostsCollection($posts, $authUser)
    {
        return $posts->map(fn($post) => $this->formatPost($post, $authUser));
    }

    public function formatPost($post, $authUser): array
    {
        return [
            'post' => $this->preparePostData($post, $authUser),
            'bookData' => $post->book()->first(),
            'user' => $post->user
        ];
    }

    private function preparePostData(Post $post, $authUser): array
    {
        return array_merge($post->toArray(), [
            'time_ago' => $this->dateFormatter->formatDate($post->created_at),
            'liked' => $post->isLikedBy($authUser),
            'disliked' => $post->isDislikedBy($authUser),
            'comments_count' => $this->repository->getPostCommentsCount($post),
        ]);
    }

    public function likePost(Request $request, int $postId): array
    {
        $post = $this->repository->findPost($postId);

        return $this->likePostsService->like($request, $post);
    }

    public function dislikePost(Request $request, int $postId): array
    {
        $post = $this->repository->findPost($postId);

        return $this->likePostsService->dislike($request, $post);
    }

    public function getPostsByUserIdFormatted(int $userId, $authUser)
    {
        $posts = $this->repository->getPostsByUserId($userId);

        return $posts->map(fn($post) => $this->formatPost($post, $authUser));
    }

    public function findPostWithUserAndWithLikes(int $postId)
    {
        return $this->repository->findPostWithUserAndWithLikes($postId);
    }
}

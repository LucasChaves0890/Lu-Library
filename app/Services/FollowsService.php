<?php

namespace App\Services;

use App\Http\Requests\FollowFormRequest;
use App\Repositories\FollowsRepository;

class FollowsService
{
    public function __construct(
        private FollowsRepository $repository,
    ) {}


    public function toggleFollow(FollowFormRequest $request): array
    {
        $followedId = $request->get('followed_user_id');
        $followingId = $request->get('following_user_id');

        $existingFollow = $this->repository->getExistingFollow($followingId, $followedId);

        if ($existingFollow) {
            return $this->unfollow($existingFollow, $followingId);
        }

        return $this->follow($followingId, $followedId);
    }

    private function follow($followingId, $followedId)
    {
        $this->repository->create($followingId, $followedId);


        $followingAfter = $this->repository->getFollowCountByFollowingId($followingId);

        return [
            'follow' => true,
            'following' => $followingAfter,
        ];
    }

    public function unfollow($existingFollow, $followingId)
    {
        $this->repository->delete($existingFollow);

        $following = $this->repository->getFollowCountByFollowingId($followingId);

        return [
            'follow' => false,
            'following' => $following,
        ];
    }

    public function getFollowingIdsByUserId(int $userId)
    {
        return $this->repository->getFollowingIdsByUserId($userId);
    }
}

<?php
namespace App\Services\Impl;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Repository\IReviewRepository;
use App\Services\IReviewService;
use Illuminate\Http\Request;
class ReviewService implements IReviewService
{
    private IReviewRepository $repository;

    public function __construct(IReviewRepository $repository) {
        $this->repository = $repository;
    }

    public function show(int $perPage , string $id) {
        return $this->repository->show($perPage,$id);
    }

    public function findById(int $id): ?Review {
        return $this->repository->getById($id);
    }

    /**
     * @throws ForbiddenException
     */
    public function add(ReviewRequest $request): Review {
        $venue_id = $request->get('venue_id');
        $user_id = $request->user()->uuid;
        if ($this->repository->checkReviewOfUserInVenue($user_id,$venue_id)) {
            throw new ForbiddenException();
        }
        $data = [
            'venue_id' => $venue_id,
            'user_id' => $user_id,
            'rating' => $request->get('rating'),
            'comment' => $request->get('comment'),
        ];
        return $this->repository->store($data);
    }

    /**
     * @throws ForbiddenException
     */

    public function delete(int $id, Request  $request): ?Review {
        $user_id = $this->repository->getById($id)->user_id;
        if ($user_id != $request->user()->uuid) {
            throw new ForbiddenException();
        }
        return $this->repository->delete($id);
    }
}

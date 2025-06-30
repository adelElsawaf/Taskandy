<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}
    public function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function getByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }
    public function getUserWithTasksByEmail($email)
    {
        return $this->userRepository->findByEmailIncludingTasks($email);
    }
    public function getUserAsEntityById($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new NotFoundHttpException("User Not Found");
        }
        return $user;
    }
}
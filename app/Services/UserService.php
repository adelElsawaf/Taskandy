<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService{
     public function __construct(
        private UserRepository $userRepository
    ) {}
    public function create(array $data){
       return $this->userRepository->create($data);
    }

    public function getByEmail($email){
        return $this->userRepository->findByEmail($email);
    }


}
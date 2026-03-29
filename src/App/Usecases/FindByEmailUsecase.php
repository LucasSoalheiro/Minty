<?php

namespace Src\App\Usecases;

use Src\App\DTO\FindByEmailDto;
use Src\App\Error\ApplicationError;
use Src\App\Error\UserNotFound;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;

class FindByEmailUsecase
{
    public function __construct(private UserRepository $userRepository) {
    }
    public function execute(FindByEmailDto $dto): User
    {   
        try{
            $user = $this->userRepository->findByEmail($dto->getEmail());
            if ($user === null) {
                throw new UserNotFound("User not found with email: " . $dto->getEmail());
            }
            return $user;
        } catch (ApplicationError $e){
            throw $e;
        }
    }
}
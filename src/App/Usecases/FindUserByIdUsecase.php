<?php

namespace Src\App\Usecases;

use Src\App\DTO\FindByIdDto;
use Src\App\Error\ApplicationError;
use Src\App\Error\UserNotFound;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;

class FindUserByIdUsecase
{
    public function __construct(private UserRepository $userRepository) {
    }
    public function execute(FindByIdDto $dto): User
    {   
        try{
            $user = $this->userRepository->findById($dto->getId());
            if ($user === null) {
                throw new UserNotFound("User not found with id: " . $dto->getId());
            }
            return $user;
        } catch (ApplicationError $e){
            throw new ApplicationError("Error finding user: " . $e->getMessage());
        }
    }
}
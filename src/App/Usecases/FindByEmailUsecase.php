<?php

namespace Src\App\Usecases;

use Src\App\DTO\FindByEmailDto;
use Src\App\Error\ApplicationError;
use Src\App\Error\EmailNotFound;
use Src\App\Error\UserNotFound;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;

class FindByEmailUsecase
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    public function execute(string $email): User
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null) {
            throw new EmailNotFound($email);
        }
        return $user;
    }
}
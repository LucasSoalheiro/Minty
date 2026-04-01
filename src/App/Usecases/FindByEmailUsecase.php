<?php

namespace Src\App\Usecases;

use Src\App\DTO\UserResponseDto;
use Src\App\Error\EmailNotFound;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;

class FindByEmailUsecase
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    public function execute(string $email): UserResponseDto
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null) {
            throw new EmailNotFound($email);
        }
        return new UserResponseDto(
            $user->getId()->__toString(),
            $user->getName(),
            $user->getEmail()
        );
    }
}
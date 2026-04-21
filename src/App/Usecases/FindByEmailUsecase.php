<?php

namespace Src\App\Usecases;

use Src\App\DTO\UserResponseDto;
use Src\App\Error\EmailNotFound;
use Src\Domain\Repository\UserRepository;

class FindByEmailUsecase
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }
    public function execute(string $email): UserResponseDto
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null) {
            throw new EmailNotFound($email);
        }
        return new UserResponseDto(
            $user->id->__toString(),
            $user->name,
            $user->email
        );
    }
}
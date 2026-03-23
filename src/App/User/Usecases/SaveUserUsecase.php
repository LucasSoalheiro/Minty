<?php
namespace Src\App\User\Usecases;

use Src\App\User\DTO\SaveUserInput;
use Src\Domain\User\Repository\PasswordHasher;
use Src\Domain\User\Repository\UserRepository;
use Src\Domain\User\User;
use Src\Domain\User\VO\Email;
use Src\Domain\User\VO\Password;

class SaveUserUsecase
{
    private UserRepository $userRepository;
    private PasswordHasher $hasher;

    public function __construct(UserRepository $userRepository, PasswordHasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;

    }
    public function execute(SaveUserInput $input): void
    {
        $email = Email::create(($input->email));
        $password = Password::create($input->password, $this->hasher);
        $data = User::create($input->name, $email, $password);
        $this->userRepository->save($data);
    }
}
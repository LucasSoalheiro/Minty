<?php

namespace Src\App\Usecases;

use Src\App\DTO\CreateAccountDto;
use Src\App\Error\UserNotFound;
use Src\Domain\Account\Account;
use Src\Domain\Account\AccountRepository;
use Src\Domain\User\UserRepository;
use Src\Domain\ValueObject\Money;

class CreateAccountUsecase
{
    public function __construct(private UserRepository $userRepository, private AccountRepository $accountRepository)
    {
    }

    public function execute(CreateAccountDto $dto): void
    {
        $user = $this->userRepository->findById($dto->getUserId());
        if ($user === null) {
            throw new UserNotFound($dto->getUserId());
        }

        $account = Account::create($dto->getName(), Money::create($dto->getBalance()), $user->getId());
        $this->accountRepository->save($account);
    }
}
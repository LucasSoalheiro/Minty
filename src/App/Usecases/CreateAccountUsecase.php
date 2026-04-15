<?php

namespace Src\App\Usecases;

use Src\App\DTO\CreateAccountDto;
use Src\App\Error\UserNotFound;
use Src\Domain\Entities\Account;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Money;

class CreateAccountUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AccountRepository $accountRepository
    ) {
    }

    public function execute(CreateAccountDto $dto): void
    {
        $user = $this->userRepository->findById($dto->userId);
        if ($user === null) {
            throw new UserNotFound($dto->userId);
        }

        $account = Account::create(
            $dto->name,
            Money::create($dto->balance),
            $user->getId()
        );
        $this->accountRepository->save($account);
    }
}
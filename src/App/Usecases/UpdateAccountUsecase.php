<?php

namespace Src\App\Usecases;

use Src\App\DTO\UpdateAccountDto;
use Src\App\Error\AccountNotFound;
use Src\Domain\Repository\AccountRepository;

class UpdateAccountUsecase
{
    public function __construct(
        private readonly AccountRepository $accountRepository
    ) {
    }

    public function execute(UpdateAccountDto $dto): void
    {
        $account = $this->accountRepository->findById($dto->accountId);
        if (!$account) {
            throw new AccountNotFound($dto->accountId);
        }

        $account->rename($dto->name);
        $this->accountRepository->save($account);
    }
}
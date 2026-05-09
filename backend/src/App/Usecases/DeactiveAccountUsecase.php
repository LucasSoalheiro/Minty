<?php

namespace Src\App\Usecases;

use Src\App\Error\AccountNotFound;
use Src\Domain\Repository\AccountRepository;

class DeactiveAccountUsecase
{
    public function __construct(
        private readonly AccountRepository $accountRepository
    ) {
    }

    public function execute(string $accountId): void
    {
        $account = $this->accountRepository->findById($accountId);
        if (!$account) {
            throw new AccountNotFound($accountId);
        }

        $account->deactivate();
        $this->accountRepository->save($account);
    }
}
<?php

namespace Src\App\Usecases;

use Src\App\DTO\FindAccountByIdResponse;
use Src\App\Error\AccountNotFound;
use Src\Domain\Repository\AccountRepository;

class FindAccountByIdUsecase
{
    public function __construct(
        private AccountRepository $accountRepository
    ) {
    }

    public function execute(string $accountId): FindAccountByIdResponse
    {
        $account = $this->accountRepository->findById($accountId);
        if (!$account) {
            throw new AccountNotFound("Account not found with this ID: $accountId");
        }

        return new FindAccountByIdResponse(
            id: $account->id,
            name: $account->name,
            balance: $account->balance->value(),
            userId: $account->userId
        );
    }
}
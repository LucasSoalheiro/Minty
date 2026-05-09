<?php

namespace Src\App\Usecases;

use Src\App\DTO\ListAccountResponse;
use Src\Domain\Repository\AccountRepository;

class ListAccountUsecase
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
    ) {
    }
    /**
     * Summary of execute
     * @param string $userId
     * @return ListAccountResponse[]
     */
    public function execute(string $userId): array
    {
        $accounts = $this->accountRepository->list($userId);
        return array_map(function ($account) {
            return new ListAccountResponse(
                id: $account->id,
                name: $account->name,
                balance: $account->balance->value(),
                isActive: $account->isActive
            );
        }, $accounts);
    }
}
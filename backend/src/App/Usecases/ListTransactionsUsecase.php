<?php

namespace Src\App\Usecases;

use Src\App\DTO\ListTransactionsDto;
use Src\App\DTO\ListTransactionsResponse;
use Src\App\Error\AccountNotFound;
use Src\Domain\Entities\TransactionStatusEnum;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\Repository\TransactionRepository;

class ListTransactionsUsecase
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountRepository $accountRepository
    ) {
    }

    /**
     * @return ListTransactionsResponse[]
     */
    public function execute(ListTransactionsDto $dto): array
    {
        $account = $this->accountRepository->findById($dto->accountId);
        if (!$account) {
            throw new AccountNotFound($dto->accountId);
        }

        $status = $dto->status ? strtoupper($dto->status) : null;
        $transactions = $this->transactionRepository->list($dto->accountId, $status);

        return array_map(function ($transaction) {
            return new ListTransactionsResponse(
                id: $transaction->id->__toString(),
                accountId: $transaction->accountId->__toString(),
                amount: $transaction->amount->value(),
                createdAt: $transaction->createdAt->format(\DateTimeInterface::ATOM),
                type: $transaction->type->name,
                status: $transaction->status->name,
                description: $transaction->description,
                categoryId: $transaction->categoryId->__toString()
            );
        }, $transactions);
    }
}
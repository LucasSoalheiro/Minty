<?php

namespace Src\App\Usecases;

use Src\App\DTO\TransferDto;
use Src\App\Error\AccountNotFound;
use Src\Domain\Account\AccountRepository;
use Src\Domain\Category\CategoryRepository;
use Src\Domain\Transaction\Transaction;
use Src\Domain\Transaction\TransactionEnum;
use Src\Domain\Transaction\TransactionRepository;
use Src\Domain\ValueObject\Money;

class TransferUsecase
{
    public function __construct(
        private AccountRepository $accountRepository,
        private TransactionRepository $transactionRepository,
        private CategoryRepository $categoryRepository
    ) {
    }

    public function execute(TransferDto $dto): void
    {
        $fromAccount = $this->accountRepository->findById($dto->fromAccountId);
        $toAccount = $this->accountRepository->findById($dto->toAccountId);

        if ($fromAccount === null) {
            throw new AccountNotFound($dto->fromAccountId);
        }

        if ($toAccount === null) {
            throw new AccountNotFound($dto->toAccountId);
        }

        $category = $this->categoryRepository->findById($dto->categoryId);

        $fromAccount->transfer($toAccount, Money::create($dto->amount));

        $this->accountRepository->save($fromAccount);
        $this->accountRepository->save($toAccount); 

        $this->transactionRepository->save(
            Transaction::create(
                $fromAccount->getId(),
                Money::create($dto->amount),
                TransactionEnum::OUTFLOW,
                $dto->description,
                $category->getId()
            )
        );
        $this->transactionRepository->save(
            Transaction::create(
                $toAccount->getId(),
                Money::create($dto->amount),
                TransactionEnum::INFLOW,
                $dto->description,
                $category->getId()
            )
        );


    }
}
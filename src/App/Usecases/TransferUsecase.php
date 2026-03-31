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
        $fromAccount = $this->accountRepository->findById($dto->getFromAccountId());
        $toAccount = $this->accountRepository->findById($dto->getToAccountId());

        if ($fromAccount === null) {
            throw new AccountNotFound($dto->getFromAccountId());
        }

        if ($toAccount === null) {
            throw new AccountNotFound($dto->getToAccountId());
        }

        $category = $this->categoryRepository->findById($dto->getCategoryId());

        $fromAccount->transfer($toAccount, Money::create($dto->getAmount()));

        $this->accountRepository->save($fromAccount);
        $this->accountRepository->save($toAccount); 

        $this->transactionRepository->save(
            Transaction::create(
                $fromAccount->getId(),
                Money::create($dto->getAmount()),
                TransactionEnum::OUTFLOW,
                $dto->getDescription(),
                $category->getId()
            )
        );
        $this->transactionRepository->save(
            Transaction::create(
                $toAccount->getId(),
                Money::create($dto->getAmount()),
                TransactionEnum::INFLOW,
                $dto->getDescription(),
                $category->getId()
            )
        );


    }
}
<?php

namespace Src\App\Usecases;

use Src\App\DTO\DepositDto;
use Src\App\Error\AccountNotFound;
use Src\App\Error\CategoryNotFound;
use Src\Domain\Account\AccountRepository;
use Src\Domain\Category\CategoryRepository;
use Src\Domain\Transaction\Transaction;
use Src\Domain\Transaction\TransactionEnum;
use Src\Domain\Transaction\TransactionRepository;
use Src\Domain\ValueObject\Money;

class DepositUsecase
{
    public function __construct(
        private AccountRepository $accountRepository,
        private TransactionRepository $transactionRepository,
        private CategoryRepository $categoryRepository
    ) {
    }

    public function execute(DepositDto $dto): void
    {
        $account = $this->accountRepository->findById($dto->getAccountId());
        if ($account === null) {
            throw new AccountNotFound($dto->getAccountId());
        }

        $category = $this->categoryRepository->findById($dto->getCategoryId());
        if ($category === null) {
            throw new CategoryNotFound($dto->getCategoryId());
        }

        $account->deposit(Money::create($dto->getAmount()));


        $this->transactionRepository->save(
            Transaction::create(
                $account->getId(),
                Money::create($dto->getAmount()),
                TransactionEnum::INFLOW,
                $dto->getDescription(),
                $category->getId()
            )
        );

        $this->accountRepository->save($account);
    }
}
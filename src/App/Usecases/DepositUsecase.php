<?php

namespace Src\App\Usecases;

use Src\App\DTO\DepositDto;
use Src\App\Error\AccountNotFound;
use Src\App\Error\CategoryInactive;
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
        private readonly AccountRepository $accountRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function execute(DepositDto $dto): void
    {
        $account = $this->accountRepository->findById($dto->accountId);
        if ($account === null) {
            throw new AccountNotFound($dto->accountId);
        }

        $category = $this->categoryRepository->findById($dto->categoryId);
        if ($category === null) {
            throw new CategoryNotFound($dto->categoryId);
        }
        if (!$category->getIsActive()) {
            throw new CategoryInactive($dto->categoryId);
        }

        $account->deposit(Money::create($dto->amount));


        $this->transactionRepository->save(
            Transaction::create(
                $account->getId(),
                Money::create($dto->amount),
                TransactionEnum::INFLOW,
                $dto->description,
                $category->getId()
            )
        );

        $this->accountRepository->save($account);
    }
}
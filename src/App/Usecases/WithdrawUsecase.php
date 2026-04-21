<?php

namespace Src\App\Usecases;

use Src\App\DTO\WithdrawDto;
use Src\App\Error\AccountNotFound;
use Src\App\Error\CategoryInactive;
use Src\App\Error\CategoryNotFound;
use Src\Domain\Entities\Transaction;
use Src\Domain\Entities\TransactionEnum;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\Repository\TransactionRepository;
use Src\Domain\ValueObject\Money;

class WithdrawUsecase
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function execute(WithdrawDto $dto): void
    {
        $account = $this->accountRepository->findById($dto->accountId);
        if (!$account) {
            throw new AccountNotFound($dto->accountId);
        }

        $category = $this->categoryRepository->findById($dto->categoryId);

        if (!$category) {
            throw new CategoryNotFound($dto->categoryId);
        }

        if (!$category->isActive) {
            throw new CategoryInactive($dto->categoryId);
        }

        $transaction = Transaction::create(
            $account->id,
            Money::create($dto->amount),
            TransactionEnum::OUTFLOW,
            $dto->description,
            $category->id
        );

        $account->withdraw(Money::create($dto->amount));
        $this->accountRepository->save($account);
        $this->transactionRepository->save($transaction);
    }
}
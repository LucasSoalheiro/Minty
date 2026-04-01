<?php

namespace Src\App\Usecases;

use Src\App\DTO\WithdrawDto;
use Src\App\Error\AccountNotFound;
use Src\App\Error\CategoryNotFound;
use Src\Domain\Account\AccountRepository;
use Src\Domain\Category\CategoryRepository;
use Src\Domain\Transaction\Transaction;
use Src\Domain\Transaction\TransactionEnum;
use Src\Domain\Transaction\TransactionRepository;
use Src\Domain\ValueObject\Money;

class WithdrawUsecase
{
    public function __construct(
        private AccountRepository $accountRepository,
        private TransactionRepository $transactionRepository,
        private CategoryRepository $categoryRepository
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

        $transaction = Transaction::create(
            $account->getId(),
            Money::create($dto->amount),
            TransactionEnum::OUTFLOW,
            $dto->description,
            $category->getId()
        );

        $account->withdraw(Money::create($dto->amount));
        $this->accountRepository->save($account);
        $this->transactionRepository->save($transaction);
    }
}
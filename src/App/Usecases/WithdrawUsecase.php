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
        $account = $this->accountRepository->findById($dto->getAccountId());
        if (!$account) {
            throw new AccountNotFound($dto->getAccountId());
        }

        $category = $this->categoryRepository->findById($dto->getCategoryId());

        if (!$category) {
            throw new CategoryNotFound($dto->getCategoryId());
        }

        $transaction = Transaction::create(
            $account->getId(),
            Money::create($dto->getAmount()),
            TransactionEnum::OUTFLOW,
            $dto->getDescription(),
            $category->getId()
        );

        $account->withdraw(Money::create($dto->getAmount()));
        $this->accountRepository->save($account);
        $this->transactionRepository->save($transaction);
    }
}
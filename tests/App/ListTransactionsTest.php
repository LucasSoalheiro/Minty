<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\ListTransactionsDto;
use Src\App\Usecases\ListTransactionsUsecase;
use Src\Domain\Entities\Account;
use Src\Domain\Entities\Transaction;
use Src\Domain\Entities\TransactionEnum;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;
use Tests\fake\FakeTransactionRepository;

class ListTransactionsTest extends TestCase
{
    private FakeTransactionRepository $transactionRepository;
    private FakeAccountRepository $accountRepository;
    private ListTransactionsUsecase $usecase;

    protected function setUp(): void
    {
        $this->transactionRepository = new FakeTransactionRepository();
        $this->accountRepository = new FakeAccountRepository();
        $this->usecase = new ListTransactionsUsecase($this->transactionRepository, $this->accountRepository);
    }

    public function testShouldListTransactions(): void
    {
        $userId = UUID::generate();
        $account = Account::create("Test Account", Money::create(1000), $userId);
        $this->accountRepository->save($account);

        $categoryId = UUID::generate();
        $t1 = Transaction::create($account->id, Money::create(100), TransactionEnum::INFLOW, "T1", $categoryId);
        $t2 = Transaction::create($account->id, Money::create(50), TransactionEnum::OUTFLOW, "T2", $categoryId);
        
        $this->transactionRepository->save($t1);
        $this->transactionRepository->save($t2);

        $dto = new ListTransactionsDto($account->id->__toString());
        $response = $this->usecase->execute($dto);

        $this->assertCount(2, $response);
        $this->assertEquals("T1", $response[0]->description);
        $this->assertEquals("T2", $response[1]->description);
    }
}
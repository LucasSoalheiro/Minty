<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\Domain\Entities\Account;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;

class FindAccountByIdTest extends TestCase
{

    private AccountRepository $accountRepository;
    protected function setUp(): void
    {
        $this->accountRepository = new FakeAccountRepository();
    }

    private function makeAccount(): Account
    {
        $account = Account::create("Savings", Money::create(1000), UUID::generate());
        $this->accountRepository->save($account);
        return $account;
    }
    public function testFindAccountById()
    {
        $account = $this->makeAccount();
        $foundAccount = $this->accountRepository->findById($account->id);
        $this->assertEquals($account, $foundAccount);
    }

    public function testFindAccountByIdWithNonExistingId()
    {
        $foundAccount = $this->accountRepository->findById("non-existing-id");
        $this->assertNull($foundAccount);
    }
}
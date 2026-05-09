<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\Usecases\DeactiveAccountUsecase;
use Src\Domain\Entities\Account;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;

class DeactiveAccountTest extends TestCase
{
    private FakeAccountRepository $accountRepository;
    private DeactiveAccountUsecase $usecase;

    protected function setUp(): void
    {
        $this->accountRepository = new FakeAccountRepository();
        $this->usecase = new DeactiveAccountUsecase($this->accountRepository);
    }

    public function testShouldDeactivateAccount(): void
    {
        $account = Account::create("Account", Money::create(1000), UUID::generate());
        $this->accountRepository->save($account);

        $this->usecase->execute($account->id->__toString());

        $updatedAccount = $this->accountRepository->findById($account->id->__toString());
        $this->assertFalse($updatedAccount->isActive);
    }
}
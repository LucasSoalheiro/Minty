<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\UpdateAccountDto;
use Src\App\Usecases\UpdateAccountUsecase;
use Src\Domain\Entities\Account;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;

class UpdateAccountTest extends TestCase
{
    private FakeAccountRepository $accountRepository;
    private UpdateAccountUsecase $usecase;

    protected function setUp(): void
    {
        $this->accountRepository = new FakeAccountRepository();
        $this->usecase = new UpdateAccountUsecase($this->accountRepository);
    }

    public function testShouldUpdateAccountName(): void
    {
        $account = Account::create("Old Name", Money::create(1000), UUID::generate());
        $this->accountRepository->save($account);

        $dto = new UpdateAccountDto($account->id->__toString(), "New Name");
        $this->usecase->execute($dto);

        $updatedAccount = $this->accountRepository->findById($account->id->__toString());
        $this->assertEquals("New Name", $updatedAccount->name);
    }
}
<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\WithdrawDto;
use Src\App\Error\AccountNotFound;
use Src\App\Error\CategoryNotFound;
use Src\App\Usecases\WithdrawUsecase;
use Src\Domain\Entities\Account;
use Src\Domain\Entities\Category;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\Repository\TransactionRepository;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;
use Tests\fake\FakeCategoryRepository;
use Tests\fake\FakeTransactionRepository;

class WithdrawTest extends TestCase
{
    private AccountRepository $accountRepository;
    private TransactionRepository $transactionRepository;
    private CategoryRepository $categoryRepository;

    public function setUp(): void
    {
        $this->accountRepository = new FakeAccountRepository();
        $this->transactionRepository = new FakeTransactionRepository();
        $this->categoryRepository = new FakeCategoryRepository();
    }

    private function makeAccount()
    {
        $account = Account::create("Test Account", Money::create(100), UUID::generate());
        $this->accountRepository->save($account);
        return $account;
    }

    private function makeCategory()
    {
        $category = Category::create("Test Category", "Description", UUID::generate());
        $this->categoryRepository->save($category);
        return $category;
    }

    public function testWithdraw(): void
    {
        $usecase = new WithdrawUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new WithdrawDto(
            $this->makeAccount()->id->__toString(),
            50,
            $this->makeCategory()->id->__toString(),
            'description'
        );
        $usecase->execute($dto);

        $account = $this->accountRepository->findById($dto->accountId);
        $this->assertNotNull($account);
        $this->assertEquals(50, $account->balance->value());
    }

    public function testWithdrawWithNonExistentAccount(): void
    {
        $this->expectException(AccountNotFound::class);
        $usecase = new WithdrawUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new WithdrawDto(
            UUID::generate()->__toString(),
            50,
            $this->makeCategory()->id->__toString(),
            'description'
        );
        $usecase->execute($dto);
    }

    public function testWithdrawWithNonExistentCategory(): void
    {
        $this->expectException(CategoryNotFound::class);
        $usecase = new WithdrawUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new WithdrawDto(
            $this->makeAccount()->id->__toString(),
            50,
            UUID::generate()->__toString(),
            'description'
        );
        $usecase->execute($dto);
    }

}
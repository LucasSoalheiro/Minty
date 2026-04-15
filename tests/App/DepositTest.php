<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\DepositDto;
use Src\App\Error\AccountNotFound;
use Src\App\Error\CategoryNotFound;
use Src\App\Usecases\DepositUsecase;
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

class DepositTest extends TestCase
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

    public function testDeposit(): void
    {
        $usecase = new DepositUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new DepositDto(
            $this->makeAccount()->getId()->__toString(),
            100,
            $this->makeCategory()->getId()->__toString(),
            'description'
        );
        $usecase->execute($dto);

        $this->assertNotNull($this->transactionRepository->list($dto->accountId));
        $this->assertNotNull($this->accountRepository->findById($dto->accountId));
        $this->assertEquals(
            200,
            $this
                ->accountRepository
                ->findById($dto
                    ->accountId)
                ->getBalance()
                ->value()
        );
    }

    public function testDepositWithNonExistentAccount(): void
    {
        $this->expectException(AccountNotFound::class);
        $usecase = new DepositUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new DepositDto(
            UUID::generate()->__toString(),
            100,
            $this->makeCategory()->getId()->__toString(),
            'description'
        );
        $usecase->execute($dto);
    }

    public function testDepositWithNonExistentCategory(): void
    {
        $this->expectException(CategoryNotFound::class);
        $usecase = new DepositUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new DepositDto(
            $this->makeAccount()->getId()->__toString(),
            100,
            UUID::generate()->__toString(),
            'description'
        );
        $usecase->execute($dto);
    }
}
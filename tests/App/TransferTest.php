<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\TransferDto;
use Src\App\Error\AccountNotFound;
use Src\App\Usecases\TransferUsecase;
use Src\Domain\Account\Account;
use Src\Domain\Account\AccountRepository;
use Src\Domain\Category\Category;
use Src\Domain\Category\CategoryRepository;
use Src\Domain\Transaction\TransactionRepository;
use Src\Domain\Transaction\TransactionStatusEnum;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;
use Tests\fake\FakeCategoryRepository;
use Tests\fake\FakeTransactionRepository;

class TransferTest extends TestCase
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

    public function testTransfer()
    {
        $account1 = $this->makeAccount();
        $account2 = $this->makeAccount();
        $category = $this->makeCategory();

        $usecase = new TransferUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new TransferDto(
            $account1->getId()->__toString(),
            $account2->getId()->__toString(),
            50,
            $category->getId()->__toString(),
            'description'
        );
        $usecase->execute($dto);

        $account1 = $this->accountRepository->findById($account1->getId());
        $account2 = $this->accountRepository->findById($account2->getId());

        $this->assertNotNull($this->transactionRepository->list($account1->getId()->__toString()));
        $this->assertNotNull($this->transactionRepository->list($account2->getId()->__toString()));

        $this->assertEquals(50, $account1?->getBalance()->value());
        $this->assertEquals(150, $account2?->getBalance()->value());
    }

    public function testTransferWithNonExistentFromAccount(): void
    {
        $this->expectException(AccountNotFound::class);
        $account2 = $this->makeAccount();
        $category = $this->makeCategory();

        $usecase = new TransferUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new TransferDto(
            UUID::generate()->__toString(),
            $account2->getId()->__toString(),
            50,
            $category->getId()->__toString(),
            'description'
        );
        $usecase->execute($dto);
    }

    public function testTransferWithNonExistentToAccount(): void
    {
        $this->expectException(AccountNotFound::class);
        $account1 = $this->makeAccount();
        $category = $this->makeCategory();

        $usecase = new TransferUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new TransferDto(
            $account1->getId()->__toString(),
            UUID::generate()->__toString(),
            50,
            $category->getId()->__toString(),
            'description'
        );
        $usecase->execute($dto);
    }

    // test the TransactionStatusEnum in the list method of the TransactionRepository
    public function testTransferWithTransactionStatusEnum(): void
    {
        $account1 = $this->makeAccount();
        $account2 = $this->makeAccount();
        $category = $this->makeCategory();

        $usecase = new TransferUsecase($this->accountRepository, $this->transactionRepository, $this->categoryRepository);
        $dto = new TransferDto(
            $account1->getId()->__toString(),
            $account2->getId()->__toString(),
            50,
            $category->getId()->__toString(),
            'description'
        );
        $usecase->execute($dto);

        $transactions1 = $this->transactionRepository->list($account1->getId()->__toString(), TransactionStatusEnum::PENDING);
        $transactions2 = $this->transactionRepository->list($account2->getId()->__toString(), TransactionStatusEnum::CANCELLED);

        $this->assertCount(1, $transactions1);
        $this->assertCount(0, $transactions2);

    }




}
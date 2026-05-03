<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\Domain\Entities\Account;
use Src\Domain\Entities\User;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;
use Tests\fake\FakeUserRepository;

class ListAccountTest extends TestCase
{
    private UserRepository $userRepository;
    private AccountRepository $accountRepository;
    public function setUp(): void
    {
        $this->userRepository = new FakeUserRepository();
        $this->accountRepository = new FakeAccountRepository();
    }

    private function makeUser(): User
    {
        $user = User::create("John Doe", Email::create("john.doe@example.com"), Password::create("P@ssw0rd"));
        $this->userRepository->save($user);
        return $user;

    }


    private function createMultipleAccountsForUser(UUID $userId): void
    {
       
        for ($i = 0; $i < 5; $i++) {
            $this->accountRepository->save(
            Account::create("Account $i", Money::create(100 * ($i + 1)), $userId)
            );
        }
    }

    public function testListAccounts(): void
    {
        $user = $this->makeUser();
        $this->createMultipleAccountsForUser($user->id);
        $accounts = $this->accountRepository->list($user->id);
        $this->assertCount(5, $accounts);
    }
}
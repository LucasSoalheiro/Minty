<?php

namespace Tests\App;

use Src\App\DTO\CreateAccountDto;
use PHPUnit\Framework\TestCase;
use Src\App\Error\UserNotFound;
use Src\App\Usecases\CreateAccountUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\AccountRepository;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeAccountRepository;
use Tests\fake\FakeUserRepository;
class CreateAccountTest extends TestCase
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
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd')));
        return $this->userRepository->findByEmail('john.doe@example.com');
    }

    private function makeAccount()
    {
        $user = $this->makeUser();
        $account = new CreateAccountDto('John\'s Account', 100, $user->id->__toString());
        return $account;
    }


    public function testCreateAccount(): void
    {
        $account = new CreateAccountUsecase($this->userRepository, $this->accountRepository);
        $dto = $this->makeAccount();
        $account->execute($dto);
        $savedAccount = $this->accountRepository->findByUserId($dto->userId);
        $this->assertCount(1, $savedAccount);
        $this->assertEquals($dto->name, $savedAccount[0]->name);
        $this->assertEquals($dto->balance, $savedAccount[0]->balance->value());

        }

    public function testCreateAccountWithNonExistentUser(): void
    {
        $this->expectException(UserNotFound::class);
        $account = new CreateAccountUsecase($this->userRepository, $this->accountRepository);
        $dto = new CreateAccountDto('John\'s Account', 100, UUID::generate()->__toString());
        $account->execute($dto);
    }


}
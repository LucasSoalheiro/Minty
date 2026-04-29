<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\Usecases\SearchByEmailUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeUserRepository;

class SearchByEmailTest extends TestCase
{
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->userRepository = new FakeUserRepository();
    }

    private function makeUser()
    {
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd271')));
    }


    public function testSearchByEmail(): void
    {
        $this->makeUser();
        $searchByEmailUsecase = new SearchByEmailUsecase($this->userRepository);
        $response = $searchByEmailUsecase->execute('john.doe@example.com');
        $this->assertIsArray($response->users);
    }


    public function testSearchByEmailWithNoResults(): void
    {
        $searchByEmailUsecase = new SearchByEmailUsecase($this->userRepository);
        $response = $searchByEmailUsecase->execute('nonexistent@example.com');
        $this->assertIsArray($response->users);
        $this->assertCount(0, $response->users);
    }
}
<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\CreateCategoryDto;
use Src\App\Usecases\CreateCategoryUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeCategoryRepository;
use Tests\fake\FakeHasher;
use Tests\fake\FakeUserRepository;

class CreateCategoryTest extends TestCase
{
    private CategoryRepository $categoryRepository;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->categoryRepository = new FakeCategoryRepository();
        $this->userRepository = new FakeUserRepository();
    }

    private function makeUser(): User
    {
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd')));
        return $this->userRepository->findByEmail('john.doe@example.com');
    }

    public function testCreateCategory(): void
    {
        $user = $this->makeUser();
        $dto = new CreateCategoryDto("Test Name", "Test Description", $user->id->__toString());
        $createCategoryUsecase = new CreateCategoryUsecase($this->categoryRepository, $this->userRepository);
        $createCategoryUsecase->execute($dto);
        $createdCategory = $this->categoryRepository->findAllByUserId($user->id->__toString());
        $this->assertCount(1, $createdCategory);
    }
}
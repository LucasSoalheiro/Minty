<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\Error\CategoryAlreadyInactive;
use Src\App\Usecases\DeactiveCategoryUsecase;
use Src\Domain\Entities\Category;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeCategoryRepository;

class DeactiveCategoryTest extends TestCase
{
    private CategoryRepository $categoryRepository;
    public function setUp(): void
    {
        $this->categoryRepository = new FakeCategoryRepository();
    }

    public function makeCategory(): Category
    {
        $category = Category::create("Test Name", null, UUID::generate());
        $this->categoryRepository->save($category);
        return $this->categoryRepository->findById($category->getId()->__toString());
    }

    public function testDeactiveCategory(): void
    {
        $category = $this->makeCategory();
        $deactiveCategoryUsecase = new DeactiveCategoryUsecase($this->categoryRepository);
        $deactiveCategoryUsecase->execute($category->getId()->__toString());
        $this->assertEquals(false, $category->getIsActive());
    }
    public function testDeactiveCategoryWithCategoryAlreadyDeactivated(): void
    {
        $category = $this->makeCategory();
        $deactiveCategoryUsecase = new DeactiveCategoryUsecase($this->categoryRepository);
        $deactiveCategoryUsecase->execute($category->getId()->__toString());
        $this->expectException(CategoryAlreadyInactive::class);
        $deactiveCategoryUsecase->execute($category->getId()->__toString());
    }
}
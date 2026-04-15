<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\UpdateCategoryDto;
use Src\App\Error\NeedToUpdateAtLeastOneField;
use Src\App\Usecases\UpdateCategoryUsecase;
use Src\Domain\Entities\Category;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\ValueObject\UUID;
use Tests\fake\FakeCategoryRepository;

class UpdateCategoryTest extends TestCase
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

    public function testUpdateCategory(): void
    {
        $category = $this->makeCategory();
        $dto = new UpdateCategoryDto($category->getId()->__toString(), "Only Test", "A Description");
        $updateCategoryUsecase = new UpdateCategoryUsecase($this->categoryRepository);
        $updateCategoryUsecase->execute($dto);
        $this->assertEquals("Only Test", $category->getName());
        $this->assertEquals("A Description", $category->getDescription());
    }

    public function testUpdateCategoryWithoutAnyData(): void
    {
        $category = $this->makeCategory();
        $dto = new UpdateCategoryDto($category->getId()->__toString(), "", "");
        $updateCategoryUsecase = new UpdateCategoryUsecase($this->categoryRepository);
        $this->expectException(NeedToUpdateAtLeastOneField::class);
        $updateCategoryUsecase->execute($dto);
    }

}
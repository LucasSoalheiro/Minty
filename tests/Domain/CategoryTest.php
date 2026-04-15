<?php 

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Src\Domain\Entities\Category;
use Src\Domain\Error\CategoryInactive;
use Src\Domain\Error\InvalidDescription;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\ValueObject\UUID;

class CategoryTest extends TestCase
{
    public function testCreateCategory(): void
    {
        $userId = UUID::generate();
        $category = Category::create("Food", "Expenses on food", $userId);
        $this->assertEquals("Food", $category->getName());
        $this->assertEquals("Expenses on food", $category->getDescription());
        $this->assertEquals($userId, $category->getUserId());
    }

    public function testCreateCategoryWithEmptyName(): void
    {
        $this->expectException(NameCannotBeNull::class);
        $userId = UUID::generate();
        Category::create("", "Description", $userId);
    }

    public function testUpdateCategoryName(): void
    {
        $userId = UUID::generate();
        $category = Category::create("Food", "Expenses on food", $userId);
        $category->updateName("Groceries");
        $this->assertEquals("Groceries", $category->getName());
    }

    public function testUpdateCategoryNameWithEmptyValue(): void
    {
        $this->expectException(NameCannotBeNull::class);
        $userId = UUID::generate();
        $category = Category::create("Food", "Expenses on food", $userId);
        $category->updateName("");
    }

    public function testUpdateCategoryDescription(): void
    {
        $userId = UUID::generate();
        $category = Category::create("Food", "Expenses on food", $userId);
        $category->updateDescription("All food-related expenses");
        $this->assertEquals("All food-related expenses", $category->getDescription());
    }

    public function testInactivateCategory(): void
    {
        $userId = UUID::generate();
        $category = Category::create("Food", "Expenses on food", $userId);
        $category->deactivate();
        $this->assertFalse($category->getIsActive());
    }

    public function testUpdateCategoryNameWhenInactive(): void
    {
        $this->expectException(CategoryInactive::class);
        $userId = UUID::generate();
        $category = Category::create("Food", "Expenses on food", $userId);
        $category->deactivate();
        $category->updateName("Groceries");
    }
    public function testUpdateCategoryDescriptionWithEmptyValue(): void
    {
        $this->expectException(InvalidDescription::class);
        $userId = UUID::generate();
        $category = Category::create("Food", "Expenses on food", $userId);
        $category->updateDescription("");
    }
}
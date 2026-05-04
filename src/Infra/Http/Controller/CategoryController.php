<?php

namespace Src\Infra\Http\Controller;

use Src\App\DTO\CreateCategoryDto;
use Src\App\DTO\UpdateCategoryDto;
use Src\App\Usecases\CreateCategoryUsecase;
use Src\App\Usecases\ListCategoriesUsecase;
use Src\App\Usecases\UpdateCategoryUsecase;
use Src\Infra\Http\Error\InvalidJsonBody;
use Src\Infra\Http\Error\ValidatorException;
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\CreateCategorySchema;
use Src\Infra\Http\Schema\UpdateCategorySchema;
use Src\Infra\Http\Security\RequiresAuth;
use Src\Infra\Http\Util\RequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    #[RequiresAuth]
    #[Route('/categories', methods: ['POST'])]
    public function create(
        Request $request,
        CreateCategoryUsecase $createCategoryUsecase,
        RequestValidator $requestValidator,
    ): Response {
        $userId = $request->attributes->get('user_id');

        $dto = $requestValidator->validate($request, CreateCategorySchema::class, CreateCategoryDto::class, ['userId' => $userId]);

        $createCategoryUsecase->execute($dto);
        return ResponseFactory::created(null, 'Category created successfully');
    }

    #[RequiresAuth]
    #[Route('/categories', methods: ['GET'])]
    public function listCategories(Request $request, ListCategoriesUsecase $listCategoriesUsecase): Response
    {
        $userId = $request->attributes->get('user_id');
        $isActive = $request->query->get('isActive');

        if ($isActive !== null) {
            $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
        }

        $categories = $listCategoriesUsecase->execute($userId, $isActive);
        return ResponseFactory::success($categories, 'Categories retrieved successfully');
    }

    #[RequiresAuth]
    #[Route('/categories/{categoryId}', methods: ['PATCH'])]
    public function updateCategory(string $categoryId, Request $request, UpdateCategoryUsecase $updateCategoryUsecase, RequestValidator $requestValidator): Response
    {
        $dto = $requestValidator->validate($request, UpdateCategorySchema::class, UpdateCategoryDto::class, ['id' => $categoryId]);
        $updateCategoryUsecase->execute($dto);
        return ResponseFactory::success(null, 'Category updated successfully');
    }
}
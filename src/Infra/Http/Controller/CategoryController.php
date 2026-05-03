<?php

namespace Src\Infra\Http\Controller;

use Src\App\DTO\CreateCategoryDto;
use Src\App\Usecases\CreateCategoryUsecase;
use Src\App\Usecases\ListCategoriesUsecase;
use Src\Infra\Http\Error\InvalidJsonBody;
use Src\Infra\Http\Error\ValidatorException;
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\CreateCategorySchema;
use Src\Infra\Http\Security\RequiresAuth;
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
        ValidatorInterface $validator
    ): Response {
        $userId = $request->attributes->get('user_id');

        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new InvalidJsonBody();
        }
        $parsedData = new CreateCategorySchema(
            name: $data['name'] ?? '',
            description: $data['description'] ?? null,
            userId: $userId
        );

        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        $dto = new CreateCategoryDto(
            name: $parsedData->name,
            description: $parsedData->description,
            userId: $parsedData->userId
        );

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
}
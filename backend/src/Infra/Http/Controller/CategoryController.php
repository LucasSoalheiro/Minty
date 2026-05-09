<?php

namespace Src\Infra\Http\Controller;

use OpenApi\Attributes as OA;
use Src\App\DTO\CreateCategoryDto;
use Src\App\DTO\UpdateCategoryDto;
use Src\App\Usecases\CreateCategoryUsecase;
use Src\App\Usecases\DeactiveCategoryUsecase;
use Src\App\Usecases\ListCategoriesUsecase;
use Src\App\Usecases\UpdateCategoryUsecase;
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
    #[OA\Post(
        path: '/categories',
        summary: 'Create a new transaction category',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Food'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Groceries and eating out')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Category created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object', nullable: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Category created successfully')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'VALIDATION_ERROR'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'USER_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
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
    #[OA\Get(
        path: '/categories',
        summary: 'List all categories for the authenticated user',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'isActive', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of categories retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'description', type: 'string', nullable: true),
                                new OA\Property(property: 'isActive', type: 'boolean')
                            ]
                        )),
                        new OA\Property(property: 'message', type: 'string', example: 'Categories retrieved successfully')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
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
    #[OA\Patch(
        path: '/categories/{categoryId}',
        summary: 'Update an existing category',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'categoryId', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Updated Category'),
                    new OA\Property(property: 'description', type: 'string', example: 'Updated description')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Category updated successfully'),
            new OA\Response(
                response: 400,
                description: 'Validation error or at least one field required',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'NEED_TO_UPDATE_AT_LEAST_ONE_FIELD'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Category not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'CATEGORY_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function updateCategory(string $categoryId, Request $request, UpdateCategoryUsecase $updateCategoryUsecase, RequestValidator $requestValidator): Response
    {
        $dto = $requestValidator->validate($request, UpdateCategorySchema::class, UpdateCategoryDto::class, ['id' => $categoryId]);
        $updateCategoryUsecase->execute($dto);
        return ResponseFactory::success(null, 'Category updated successfully');
    }

    #[RequiresAuth]
    #[Route("/categories/{categoryId}", methods: ["DELETE"])]
    #[OA\Delete(
        path: '/categories/{categoryId}',
        summary: 'Deactivate a category',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'categoryId', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Category deactivated successfully'),
            new OA\Response(
                response: 404,
                description: 'Category not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'CATEGORY_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Category already inactive',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'CATEGORY_ALREADY_INACTIVE'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function deactiveCategory(string $categoryId, DeactiveCategoryUsecase $deactiveCategoryUsecase): Response
    {
        $deactiveCategoryUsecase->execute($categoryId);
        return ResponseFactory::noContent();
    }
}
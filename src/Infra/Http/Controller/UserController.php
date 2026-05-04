<?php
namespace Src\Infra\Http\Controller;

use OpenApi\Attributes as OA;
use Src\App\DTO\ChangeEmailDto;
use Src\App\DTO\ChangePasswordDto;
use Src\App\DTO\ChangeUserNameDto;
use Src\App\DTO\CreateUserDto;
use Src\App\Usecases\ChangeEmailUsecase;
use Src\App\Usecases\ChangePasswordUsecase;
use Src\App\Usecases\ChangeUserNameUsecase;
use Src\App\Usecases\CreateUserUsecase;
use Src\App\Usecases\FindByEmailUsecase;
use Src\App\Usecases\FindUserByIdUsecase;
use Src\App\Usecases\SearchByEmailUsecase;
use Src\Infra\Http\Error\InvalidJsonBody;
use Src\Infra\Http\Error\ParamsException;
use Src\Infra\Http\Error\QueryException;
use Src\Infra\Http\Error\ValidatorException;
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\CreateUserSchema;
use Src\Infra\Http\Schema\FindByEmailSchema;
use Src\Infra\Http\Schema\FindByIdSchema;
use Src\Infra\Http\Schema\UpdateEmailSchema;
use Src\Infra\Http\Schema\UpdateNameSchema;
use Src\Infra\Http\Schema\UpdatePasswordSchema;
use Src\Infra\Http\Security\RequiresAuth;
use Src\Infra\Http\Util\RequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/users', methods: ["POST"])]
    #[OA\Post(
        path: '/users',
        summary: 'Create a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["name", "email", "password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", nullable: true),
                        new OA\Property(property: "message", type: "string", example: "User Created")
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
                response: 409,
                description: 'Email already in use',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'EMAIL_ALREADY_IN_USE'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function create(
        Request $request,
        CreateUserUsecase $createUserUsecase,
        RequestValidator $requestValidator
    ): Response {
        $dto = $requestValidator->validate(
            $request,
            CreateUserSchema::class,
            CreateUserDto::class
        );
        $createUserUsecase->execute($dto);
        return ResponseFactory::created(null, 'User Created');
    }

    #[RequiresAuth]
    #[Route('/users/me', methods: ['GET'])]
    #[OA\Get(
        path: '/users/me',
        summary: 'Get current authenticated user details',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User details found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'email', type: 'string')
                        ]),
                        new OA\Property(property: 'message', type: 'string', example: 'User Found')
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
    public function findById(
        Request $request,
        FindUserByIdUsecase $findUserByIdUsecase,
        ValidatorInterface $validator
    ): Response {
        $id = $request->attributes->get('user_id');
        $parsedData = new FindByIdSchema($id);
        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new ValidatorException((string) $errors);
        }

        $response = $findUserByIdUsecase->execute($parsedData->id);
        return ResponseFactory::success($response, 'User Found');
    }

    #[Route('/users/email/{email}', methods: ['GET'])]
    #[OA\Get(
        path: '/users/email/{email}',
        summary: 'Find user by email',
        parameters: [
            new OA\Parameter(name: 'email', in: 'path', required: true, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object'),
                        new OA\Property(property: 'message', type: 'string', example: 'User Found')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'EMAIL_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function findByEmail(
        string $email,
        FindByEmailUsecase $findByEmailUsecase,
        ValidatorInterface $validator
    ): Response {
        if ($email === '') {
            throw new ParamsException("Email parameter is required");
        }
        $parsedData = new FindByEmailSchema(email: $email);

        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new ValidatorException((string) $errors);
        }

        $response = $findByEmailUsecase->execute($parsedData->email);

        return ResponseFactory::success($response, 'User Found');
    }

    #[Route('/users/search', methods: ['GET'])]
    #[OA\Get(
        path: '/users/search',
        summary: 'Search users by email prefix',
        parameters: [
            new OA\Parameter(name: 'email', in: 'query', required: true, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Users found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'message', type: 'string', example: 'Users Found')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Query parameter error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'QUERY_ERROR'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function searchByEmail(
        Request $request,
        SearchByEmailUsecase $searchByEmailUsecase,
    ): Response {
        $search = $request->query->get('email');

        if ($search === null) {
            throw new QueryException("Search term is required");
        }

        $response = $searchByEmailUsecase->execute($search);

        return ResponseFactory::success($response->users, 'Users Found');
    }

    #[RequiresAuth]
    #[Route('/users/email', methods: ['PATCH'])]
    #[OA\Patch(
        path: '/users/email',
        summary: 'Update current user email',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Email updated successfully'),
            new OA\Response(
                response: 409,
                description: 'Email already in use or should be different',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'EMAIL_ALREADY_IN_USE'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Password does not match',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'PASSWORD_DOES_NOT_MATCH'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function updateEmail(
        Request $request,
        ChangeEmailUsecase $changeEmailUsecase,
        RequestValidator $requestValidator
    ): Response {
        $id = $request->attributes->get('user_id');
        $dto = $requestValidator->validate(
            $request,
            UpdateEmailSchema::class,
            ChangeEmailDto::class,
            ['id' => $id]
        );
        $changeEmailUsecase->execute($dto);

        return ResponseFactory::success(null, "Email Updated");
    }

    #[RequiresAuth]
    #[Route("/users/password", methods: ["PATCH"])]
    #[OA\Patch(
        path: '/users/password',
        summary: 'Update user password',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'email', in: 'query', required: true, schema: new OA\Schema(type: 'string'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['newPassword', 'oldPassword'],
                properties: [
                    new OA\Property(property: 'newPassword', type: 'string'),
                    new OA\Property(property: 'oldPassword', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Password updated successfully'),
            new OA\Response(
                response: 401,
                description: 'Wrong password',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'WRONG_PASSWORD'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Weak password',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'WEAK_PASSWORD'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function updatePassword(
        Request $request,
        ChangePasswordUsecase $changePasswordUsecase,
        RequestValidator $requestValidator
    ): Response {
        $email = $request->query->get('email');
        $dto = $requestValidator->validate(
            $request,
            UpdatePasswordSchema::class,
            ChangePasswordDto::class,
            ['email' => $email]
        );

        $changePasswordUsecase->execute($dto);
        return ResponseFactory::success(null, "Password Updated");
    }

    #[RequiresAuth]
    #[Route("/users/name", methods: ["PATCH"])]
    #[OA\Patch(
        path: '/users/name',
        summary: 'Update user name',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'email', in: 'query', required: true, schema: new OA\Schema(type: 'string'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Updated')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Name updated successfully'),
            new OA\Response(
                response: 400,
                description: 'Validation error or Name cannot be null',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'VALIDATION_ERROR'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function updateName(
        Request $request,
        ChangeUserNameUsecase $changeUserNameUsecase,
        RequestValidator $requestValidator
    ): Response {
        $email = $request->query->get('email');
        $dto = $requestValidator->validate(
            $request,
            UpdateNameSchema::class,
            ChangeUserNameDto::class,
            ['email' => $email]
        );
        $changeUserNameUsecase->execute($dto);
        return ResponseFactory::success(null, "Name updated");
    }
}
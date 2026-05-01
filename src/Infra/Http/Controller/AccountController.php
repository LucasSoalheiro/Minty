<?php
namespace Src\Infra\Http\Controller;

use OpenApi\Attributes as OA;
use Src\App\DTO\CreateAccountDto;
use Src\App\Usecases\CreateAccountUsecase;
use Src\Infra\Http\Error\InvalidJsonBody;
use Src\Infra\Http\Error\ValidatorException;
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\CreateAccountSchema;
use Src\Infra\Http\Security\RequiresAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountController extends AbstractController
{
    #[RequiresAuth]
    #[Route("/accounts", methods: ["POST"])]
    #[OA\Post(
        path: '/accounts',
        summary: 'Create a new account for the authenticated user',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name', 'balance'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Savings'),
                    new OA\Property(property: 'balance', type: 'integer', example: 1000)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Account created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object', nullable: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Account created successfully')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid JSON or Validation Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'VALIDATION_ERROR'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized - Invalid or missing token',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'integer', example: 401),
                        new OA\Property(property: 'message', type: 'string', example: 'Token Null')
                    ]
                )
            )
        ]
    )]
    public function create(
        Request $request,
        CreateAccountUsecase $createAccountUsecase,
        ValidatorInterface $validator
    ): Response {
        $userId = $request->attributes->get('user_id');
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new InvalidJsonBody();
        }

        $parsedData = new CreateAccountSchema(
            name: $data['name'],
            balance: $data['balance'],
            userId: $userId

        );

        $errors = $validator->validate($parsedData);

        if (\count($errors) > 0) {
            throw new ValidatorException((string) $errors);
        }

        $dto = new CreateAccountDto(
            name: $parsedData->name,
            balance: $parsedData->balance,
            userId: $parsedData->userId
        );

        $createAccountUsecase->execute($dto);
        return ResponseFactory::created(null, "Account created successfully");
    }
}
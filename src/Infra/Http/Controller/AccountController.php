<?php
namespace Src\Infra\Http\Controller;

use OpenApi\Attributes as OA;
use Src\App\DTO\CreateAccountDto;
use Src\App\DTO\DepositDto;
use Src\App\DTO\TransferDto;
use Src\App\DTO\WithdrawDto;
use Src\App\Usecases\CreateAccountUsecase;
use Src\App\Usecases\DepositUsecase;
use Src\App\Usecases\FindAccountByIdUsecase;
use Src\App\Usecases\ListAccountUsecase;
use Src\App\Usecases\TransferUsecase;
use Src\App\Usecases\WithdrawUsecase;
use Src\Infra\Http\Error\ParamsException;
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\CreateAccountSchema;
use Src\Infra\Http\Schema\DepositSchema;
use Src\Infra\Http\Schema\TransferSchema;
use Src\Infra\Http\Schema\WithdrawSchema;
use Src\Infra\Http\Security\RequiresAuth;
use Src\Infra\Http\Util\RequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
        RequestValidator $requestValidator
    ): Response {
        $userId = $request->attributes->get('user_id');
        $dto = $requestValidator->validate(
            $request,
            CreateAccountSchema::class,
            CreateAccountDto::class,
            ['userId' => $userId]
        );

        $createAccountUsecase->execute($dto);
        return ResponseFactory::created(null, "Account created successfully");
    }

    #[RequiresAuth]
    #[Route("/accounts", methods: ["GET"])]
    #[OA\Get(
        path: '/accounts',
        summary: 'List all accounts for the authenticated user',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of accounts retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'balance', type: 'integer'),
                                new OA\Property(property: 'isActive', type: 'boolean')
                            ]
                        )),
                        new OA\Property(property: 'message', type: 'string', example: 'Accounts retrieved successfully')
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
    public function listAccounts(Request $request, ListAccountUsecase $listAccountUsecase): Response
    {
        $userId = $request->attributes->get('user_id');
        $accounts = $listAccountUsecase->execute($userId);
        return ResponseFactory::success($accounts, "Accounts retrieved successfully");
    }

    #[RequiresAuth]
    #[Route("/accounts/{accountId}", methods: ["GET"])]
    #[OA\Get(
        path: '/accounts/{accountId}',
        summary: 'Get details of a specific account',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'accountId', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Account details retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'balance', type: 'integer'),
                            new OA\Property(property: 'userId', type: 'string', format: 'uuid')
                        ]),
                        new OA\Property(property: 'message', type: 'string', example: 'Account retrieved successfully')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid Account ID',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'PARAMS_ERROR'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Account not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'ACCOUNT_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function getAccountById(string $accountId, FindAccountByIdUsecase $findAccountByIdUsecase): Response
    {
        if (!$accountId) {
            throw new ParamsException("Account ID is required");
        }
        $account = $findAccountByIdUsecase->execute($accountId);
        return ResponseFactory::success($account, "Account retrieved successfully");
    }

    #[RequiresAuth]
    #[Route("/accounts/{accountId}/deposit", methods: ["POST"])]
    #[OA\Post(
        path: '/accounts/{accountId}/deposit',
        summary: 'Deposit an amount into an account',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'accountId', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['amount', 'categoryId'],
                properties: [
                    new OA\Property(property: 'amount', type: 'integer', example: 100),
                    new OA\Property(property: 'categoryId', type: 'string', format: 'uuid', example: 'uuid-v4'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Salary')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Deposit successful'),
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
                description: 'Account or Category not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'ACCOUNT_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Category is inactive',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'CATEGORY_INACTIVE'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function deposit(string $accountId, Request $request, DepositUsecase $depositUsecase, RequestValidator $requestValidator): Response
    {
        if (!$accountId) {
            throw new ParamsException("Account ID is required");
        }
        $dto = $requestValidator->validate(
            $request,
            DepositSchema::class,
            DepositDto::class,
            ['accountId' => $accountId]
        );
        $depositUsecase->execute($dto);
        return ResponseFactory::success(null, "Deposit successful");
    }

    #[RequiresAuth]
    #[Route("/accounts/{accountId}/withdraw", methods: ["POST"])]
    #[OA\Post(
        path: '/accounts/{accountId}/withdraw',
        summary: 'Withdraw an amount from an account',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'accountId', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['amount', 'categoryId'],
                properties: [
                    new OA\Property(property: 'amount', type: 'integer', example: 50),
                    new OA\Property(property: 'categoryId', type: 'string', format: 'uuid', example: 'uuid-v4'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Groceries')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Withdraw successful'),
            new OA\Response(
                response: 422,
                description: 'Insufficient funds',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'INSUFFICIENT_FUNDS'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Account or Category not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'ACCOUNT_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function withdraw(string $accountId, Request $request, WithdrawUsecase $withdrawUsecase, RequestValidator $requestValidator): Response
    {
        if (!$accountId) {
            throw new ParamsException("Account ID is required");
        }
        $dto = $requestValidator->validate(
            $request,
            WithdrawSchema::class,
            WithdrawDto::class,
            ["accountId" => $accountId]
        );
        $withdrawUsecase->execute($dto);
        return ResponseFactory::success(null, "Withdraw successful");
    }

    #[RequiresAuth]
    #[Route("/accounts/{accountId}/transfer", methods: ["POST"])]
    #[OA\Post(
        path: '/accounts/{accountId}/transfer',
        summary: 'Transfer an amount between accounts',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(name: 'accountId', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['toAccountId', 'amount', 'categoryId'],
                properties: [
                    new OA\Property(property: 'toAccountId', type: 'string', format: 'uuid', example: 'uuid-v4'),
                    new OA\Property(property: 'amount', type: 'integer', example: 100),
                    new OA\Property(property: 'categoryId', type: 'string', format: 'uuid', example: 'uuid-v4'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Rent')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Transfer successful'),
            new OA\Response(
                response: 400,
                description: 'Invalid transfer or validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'INVALID_TRANSFER'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Source or Destination Account not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'ACCOUNT_NOT_FOUND'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function transfer(string $accountId, Request $request, TransferUsecase $transferUsecase, RequestValidator $requestValidator): Response
    {
        if (!$accountId) {
            throw new ParamsException("Account ID is required");
        }
        $dto = $requestValidator->validate(
            $request,
            TransferSchema::class,
            TransferDto::class,
            ["fromAccountId" => $accountId]
        );
        $transferUsecase->execute($dto);
        return ResponseFactory::success(null, "Transfer successful");
    }
}
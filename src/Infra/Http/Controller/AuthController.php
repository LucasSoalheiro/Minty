<?php

namespace Src\Infra\Http\Controller;

use OpenApi\Attributes as OA;
use Src\App\DTO\LoginDto;
use Src\App\Usecases\LoginUsecase;
use Src\App\Usecases\LogoutUsecase;
use Src\App\Usecases\RefreshTokenUsecase;
use Src\Infra\Http\Error\CookieException;
use Src\Infra\Http\Error\InvalidJsonBody;
use Src\Infra\Http\Error\ValidatorException;
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\LoginSchema;
use Src\Infra\Http\Security\RequiresAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    #[Route('/login', methods: ['POST'])]
    #[OA\Post(
        path: '/login',
        summary: 'Authenticate user and return tokens',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'access_token', type: 'string'),
                            new OA\Property(property: 'refresh', type: 'string')
                        ]),
                        new OA\Property(property: 'message', type: 'string', example: 'Login Successful')
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
    public function login(
        Request $request,
        LoginUsecase $authenticateUsecase,
        ValidatorInterface $validator
    ) {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new InvalidJsonBody();
        }
        $parsedData = new LoginSchema(
            $data['email'],
            $data['password']
        );
        $errors = $validator->validate($parsedData);

        if (\count($errors) > 0) {
            throw new ValidatorException((string) $errors);
        }
        $dto = new LoginDto(
            email: $parsedData->email,
            password: $parsedData->password
        );

        $response = $authenticateUsecase->execute($dto);
        $responseHttp = ResponseFactory::success([
            "access_token" => $response->accessToken,
            "refresh" => $response->refreshToken
        ], "Login Successful");
        $responseHttp->headers->setCookie(
            new Cookie(
                'refresh_token',
                $response->refreshToken,
                time() + (7 * 24 * 60 * 60),
                '/',
                null,
                true,
                true,
                false,
                'Strict'
            )
        );
        return $responseHttp;
    }

    #[RequiresAuth]
    #[Route('/logout', methods: ['POST'])]
    #[OA\Post(
        path: '/logout',
        summary: 'Logout user and invalidate session',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(response: 204, description: 'Logout successful'),
            new OA\Response(
                response: 400,
                description: 'Cookie error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'COOKIE_ERROR'),
                        new OA\Property(property: 'message', type: 'string', example: 'Refresh token not found in cookies')
                    ]
                )
            )
        ]
    )]
    public function logout(
        Request $request,
        LogoutUsecase $logoutUsecase
    ): Response {
        $refreshToken = $request->cookies->get('refresh_token');
        if (!$refreshToken) {
            throw new CookieException("Refresh token not found in cookies");
        }
        $logoutUsecase->execute($refreshToken);
        $response = ResponseFactory::noContent();
        $response->headers->clearCookie('refresh_token');
        return $response;
    }

    #[Route("/refresh", methods: ['POST'])]
    #[OA\Post(
        path: '/refresh',
        summary: 'Refresh access token using refresh token cookie',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token refreshed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'access_token', type: 'string'),
                            new OA\Property(property: 'refresh', type: 'string')
                        ]),
                        new OA\Property(property: 'message', type: 'string', example: 'Token Refreshed')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Cookie or Refresh Token error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'boolean', example: true),
                        new OA\Property(property: 'code', type: 'string', example: 'INVALID_REFRESH_TOKEN'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function refresh(
        Request $request,
        RefreshTokenUsecase $refreshTokenUsecase
    ): Response {
        $refreshToken = $request->cookies->get('refresh_token');
        if (!$refreshToken) {
            throw new CookieException("Refresh token not found in cookies");
        }
        $response = $refreshTokenUsecase->execute($refreshToken);
        $responseHttp = ResponseFactory::success([
            "access_token" => $response->accessToken,
            "refresh" => $response->refreshToken
        ], "Token Refreshed");
        $responseHttp->headers->setCookie(
            new Cookie(
                'refresh_token',
                $response->refreshToken,
                time() + (7 * 24 * 60 * 60),
                '/',
                null,
                true,
                true,
                false,
                'Strict'
            )
        );
        return $responseHttp;   
    }
}
<?php

namespace Src\Infra\Http\Controller;

use Src\App\DTO\LoginDto;
use Src\App\Usecases\LoginUsecase;
use Src\App\Usecases\LogoutUsecase;
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
    public function login(
        Request $request,
        LoginUsecase $authenticateUsecase,
        ValidatorInterface $validator
    ) {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new \InvalidArgumentException("Invalid JSON body");
        }
        $parsedData = new LoginSchema(
            $data['email'],
            $data['password']
        );
        $errors = $validator->validate($parsedData);

        if (\count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
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
    public function logout(
        Request $request,
        LogoutUsecase $logoutUsecase
    ): Response {
        $refreshToken = $request->cookies->get('refresh_token');
        if (!$refreshToken) {
            throw new \InvalidArgumentException("Refresh token not found in cookies");
        }
        $logoutUsecase->execute($refreshToken);
        $response =  ResponseFactory::noContent();
        $response->headers->clearCookie('refresh_token');
        return $response;
    }
}
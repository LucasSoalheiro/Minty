<?php

namespace Src\Infra\Http\Controller;

use Src\App\DTO\LoginDto;
use Src\App\Usecases\LoginUsecase;
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\LoginSchema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        return ResponseFactory::success([ "access_token" => $response->accessToken, "refresh" => $response->refreshToken ], "Login Successful");
    }
}
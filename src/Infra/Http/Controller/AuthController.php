<?php

namespace Src\Infra\Http\Controller;

use Src\App\DTO\AuthenticateDto;
use Src\App\Error\EmailNotFound;
use Src\App\Error\WrongPassword;
use Src\App\Usecases\AuthenticateUsecase;
use Src\Infra\Http\Schema\LoginSchema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    #[Route('/login', methods: ['POST'])]
    public function login(
        Request $request,
        AuthenticateUsecase $authenticateUsecase,
        ValidatorInterface $validator
    ) {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException("Invalid JSON body");
        }
        try {
            $parsedData = new LoginSchema(
                $data['email'],
                $data['password']
            );
            $errors = $validator->validate($parsedData);

            if (\count($errors) > 0) {
                throw new BadRequestHttpException((string) $errors);
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $dto = new AuthenticateDto(
            email: $parsedData->email,
            password: $parsedData->password
        );

        try {
            $authenticateUsecase->execute($dto);
        } catch (\Exception $e) {
            if ($e instanceof EmailNotFound) {
                throw new NotFoundHttpException($e->getMessage());
            }
            if ($e instanceof WrongPassword) {
                throw new UnauthorizedHttpException($e->getMessage());
            }
            throw new BadRequestHttpException($e->getMessage());
        }
        return new JsonResponse([
            'status' => "success",
            'message' => 'Login successful',
            'data' => $data
        ], 200);
    }
}
<?php
namespace Src\Infra\Http\Controller;

use Src\App\DTO\ChangeEmailDto;
use Src\App\DTO\CreateUserDto;
use Src\App\Error\EmailAlreadyInUse;
use Src\App\Error\EmailNotFound;
use Src\App\Error\UserNotFound;
use Src\App\Error\WrongPassword;
use Src\App\Usecases\ChangeEmailUsecase;
use Src\App\Usecases\CreateUserUsecase;
use Src\App\Usecases\FindByEmailUsecase;
use Src\Infra\Http\Schema\CreateUserSchema;
use Src\Infra\Http\Schema\FindByEmailSchema;
use Src\Infra\Http\Schema\UpdateEmailSchema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/users', methods: ["POST"])]
    public function create(
        Request $request,
        CreateUserUsecase $createUserUsecase,
        ValidatorInterface $validator
    ): Response {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            throw new BadRequestHttpException("Invalid JSON body");
        }
        try {

            $parsedData = new CreateUserSchema(
                name: $data['name'],
                email: $data['email'],
                password: $data['password']
            );

            $errors = $validator->validate($parsedData);

            if (\count($errors) > 0) {
                throw new BadRequestHttpException((string) $errors);
            }

            $dto = new CreateUserDto(
                name: $parsedData->name,
                email: $parsedData->email,
                password: $parsedData->password
            );

            $createUserUsecase->execute($dto);
            return new JsonResponse([
                'status' => 'success',
                'message' => 'User Created',
                'data' => null
            ], 201);

        } catch (\Exception $e) {
            if ($e instanceof EmailAlreadyInUse) {
                throw new ConflictHttpException($e->getMessage());
            }
            throw new BadRequestHttpException($e->getMessage());
        }
    }
    #[Route('/users', methods: ['GET'])]
    public function findByEmail(
        Request $request,
        FindByEmailUsecase $findByEmailUsecase,
        ValidatorInterface $validator
    ): Response {
        $email = $request->query->get('email');

        if ($email === null) {
            throw new BadRequestHttpException("Email is required");
        }
        try {
            $parsedData = new FindByEmailSchema(email: $email);

            $errors = $validator->validate($parsedData);
            if (\count($errors) > 0) {
                throw new BadRequestHttpException((string) $errors);
            }

            $response = $findByEmailUsecase->execute($parsedData->email);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'User found',
                'data' => [
                    "id" => $response->id,
                    "name" => $response->name,
                    "email" => $response->email
                ]
            ], 200);

        } catch (\Exception $e) {
            if ($e instanceof EmailNotFound) {
                throw new NotFoundHttpException($e->getMessage());
            }
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Route('/users/email/{id}', methods: ['PATCH'])]
    public function updateEmail(
        string $id,
        Request $request,
        ChangeEmailUsecase $changeEmailUsecase,
        ValidatorInterface $validator
    ): Response {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException("Invalid JSON body");
        }
        try {
            $parsedData = new UpdateEmailSchema(
                id: $id,
                email: $data['email'],
                password: $data['password']
            );
            $errors = $validator->validate($parsedData);
            if (\count($errors) > 0) {
                throw new BadRequestHttpException((string) $errors);
            }
            $dto = new ChangeEmailDto($parsedData->id, $parsedData->email, $parsedData->password);
            $changeEmailUsecase->execute($dto);

            return new JsonResponse([
                'status' => "success",
                "message" => "Email updated",
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            if ($e instanceof UserNotFound) {
                throw new NotFoundHttpException($e->getMessage());
            }
            if ($e instanceof EmailAlreadyInUse) {
                throw new ConflictHttpException($e->getMessage());
            }
            if ($e instanceof WrongPassword) {
                throw new UnauthorizedHttpException($e->getMessage());
            }
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
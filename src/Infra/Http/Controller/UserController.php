<?php
namespace Src\Infra\Http\Controller;

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
use Src\Infra\Http\Response\ResponseFactory;
use Src\Infra\Http\Schema\CreateUserSchema;
use Src\Infra\Http\Schema\FindByEmailSchema;
use Src\Infra\Http\Schema\FindByIdSchema;
use Src\Infra\Http\Schema\UpdateEmailSchema;
use Src\Infra\Http\Schema\UpdateNameSchema;
use Src\Infra\Http\Schema\UpdatePasswordSchema;
use Src\Infra\Http\Security\RequiresAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            throw new \InvalidArgumentException("Invalid JSON body");
        }

        $parsedData = new CreateUserSchema(
            name: $data['name'],
            email: $data['email'],
            password: $data['password']
        );

        $errors = $validator->validate($parsedData);

        if (\count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        $dto = new CreateUserDto(
            name: $parsedData->name,
            email: $parsedData->email,
            password: $parsedData->password
        );

        $createUserUsecase->execute($dto);
        return ResponseFactory::created(null, 'User Created');
    }

    #[RequiresAuth]
    #[Route('/users/me', methods: ['GET'])]
    public function findById(
        Request $request,
        FindUserByIdUsecase $findUserByIdUsecase,
        ValidatorInterface $validator
    ): Response {
        $id = $request->attributes->get('user_id');
        $parsedData = new FindByIdSchema($id);
        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        $response = $findUserByIdUsecase->execute($parsedData->id);
        return ResponseFactory::success($response, 'User Found');
    }

    #[Route('/users/email/{email}', methods: ['GET'])]
    public function findByEmail(
        string $email,
        FindByEmailUsecase $findByEmailUsecase,
        ValidatorInterface $validator
    ): Response {
        $parsedData = new FindByEmailSchema(email: $email);

        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        $response = $findByEmailUsecase->execute($parsedData->email);

        return ResponseFactory::success($response, 'User Found');
    }

    #[Route('/users/search', methods: ['GET'])]
    public function searchByEmail(
        Request $request,
        SearchByEmailUsecase $searchByEmailUsecase,
    ): Response {
        $search = $request->query->get('email');

        if ($search === null) {
            throw new \InvalidArgumentException("Search term is required");
        }

        $response = $searchByEmailUsecase->execute($search);

        return ResponseFactory::success($response->users, 'Users Found');
    }

    #[RequiresAuth]
    #[Route('/users/email', methods: ['PATCH'])]
    public function updateEmail(
        Request $request,
        ChangeEmailUsecase $changeEmailUsecase,
        ValidatorInterface $validator
    ): Response {
        $id = $request->attributes->get('user_id');
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new \InvalidArgumentException("Invalid JSON body");
        }
        $parsedData = new UpdateEmailSchema(
            id: $id,
            email: $data['email'],
            password: $data['password']
        );
        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }
        $dto = new ChangeEmailDto($parsedData->id, $parsedData->email, $parsedData->password);
        $changeEmailUsecase->execute($dto);

        return ResponseFactory::success(null, "Email Updated");
    }

    #[RequiresAuth]
    #[Route("/users/password", methods: ["PATCH"])]
    public function updatePassword(
        Request $request,
        ChangePasswordUsecase $changePasswordUsecase,
        ValidatorInterface $validator
    ): Response {
        $email = $request->query->get('email');
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new \InvalidArgumentException("Invalid JSON body");
        }
        $parsedData = new UpdatePasswordSchema(
            $email,
            $data['newPassword'],
            $data['oldPassword']
        );

        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        $dto = new ChangePasswordDto(
            $parsedData->email,
            $parsedData->oldPassword,
            $parsedData->newPassword
        );

        $changePasswordUsecase->execute($dto);
        return ResponseFactory::success(null, "Password Updated");
    }

    #[RequiresAuth]
    #[Route("/users/name", methods: ["PATCH"])]
    public function updateName(
        Request $request,
        ChangeUserNameUsecase $changeUserNameUsecase,
        ValidatorInterface $validator
    ): Response {
        $email = $request->query->get('email');
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new \InvalidArgumentException("Invalid JSON body");
        }
        $parsedData = new UpdateNameSchema(
            $email,
            $data['name']
        );

        $errors = $validator->validate($parsedData);
        if (\count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }
        $dto = new ChangeUserNameDto($parsedData->name, $parsedData->email);
        $changeUserNameUsecase->execute($dto);
        return ResponseFactory::success(null, "Name updated");
    }
}
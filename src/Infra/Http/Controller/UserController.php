<?php
namespace Src\Infra\Http\Controller;

use Src\App\DTO\CreateUserDto;
use Src\App\Usecases\CreateUserUsecase;
use Src\Infra\Http\Schema\UserSchema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/users', methods: ["POST"])]
    public function createUser(Request $request, CreateUserUsecase $createUserUsecase, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException("Invalid JSON body");
        }

        try{
        $parsedData = new UserSchema(
            $data['name'],
            $data['email'],
            $data['password']
        );
        $errors = $validator->validate($parsedData);

        if (\count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }} catch (\Exception $e){
            throw new BadRequestHttpException($e->getMessage());
        }
        $dto = new CreateUserDto(
            name: $parsedData->name,
            email: $parsedData->email,
            password: $parsedData->password
        );
        try {
            $createUserUsecase->execute($dto);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return new JsonResponse([
            'status' => 'success',
            'message' => 'User Created',
            'data' => $data
        ], 201);
    }
}
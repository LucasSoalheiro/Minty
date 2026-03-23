<?php
namespace Src\Infra\Controller;

use Src\App\User\DTO\SaveUserInput;
use Src\App\User\Usecases\SaveUserUsecase;
class UserController
{
    private SaveUserUsecase $saveUserUsecase;

    public function __construct(SaveUserUsecase $saveUserUsecase)
    {
        $this->saveUserUsecase = $saveUserUsecase;
    }

    public function save()
    {
        $raw = file_get_contents("php://input");
        $data = json_decode($raw, true);

        $input = new SaveUserInput($data['name'], $data['email'], $data['password']);
        $this->saveUserUsecase->execute($input);
    }
}
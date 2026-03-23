<?php
namespace Src\Infra\Db\User\Mapper;

use Src\Domain\User\User;
use Src\Domain\User\VO\Email;
use Src\Domain\User\VO\Password;
use Src\Infra\Db\User\Model\UserModel;
class UserMapper
{
    public static function toDomain(UserModel $data): User
    {
        return User::restore($data->name, Email::restore($data->email), Password::restore($data->password_hash));
    }

    public static function toPersistence(User $data): UserModel
    {
        return new UserModel($data->getUuid(), $data->getName(), $data->getEmail(), $data->getPassword());
    }
}
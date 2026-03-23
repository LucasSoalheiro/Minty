<?php
namespace Src\Infra\Db\User\Model;
class UserModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password_hash,
    ) {
    }
}
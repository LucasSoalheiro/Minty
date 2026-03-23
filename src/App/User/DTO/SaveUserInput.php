<?php
namespace Src\App\User\DTO;
readonly class SaveUserInput
{

    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {

    }
}
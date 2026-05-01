<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class LoginSchema {
    public function __construct(
        #[Constraints\NotBlank(message: "Name is required")]
        #[Constraints\Email(message: "Invalid email format")]
        public string $email,

        #[Constraints\NotBlank(message: "Password is required")]
        public string $password,
    )
    {
       
    }
}
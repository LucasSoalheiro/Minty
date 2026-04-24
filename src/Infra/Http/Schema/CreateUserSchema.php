<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;


class CreateUserSchema
{
    public function __construct(
        #[Constraints\NotBlank(message: "Name is required")]
        #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
        public $name,

        #[Constraints\NotBlank(message: "Email is required")]
        #[Constraints\Email(message: "Invalid email format")]
        public $email,

        #[Constraints\NotBlank(message: "Password is required")]
        #[Constraints\PasswordStrength(minScore: 1)]
        #[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
        public $password
    ) {
    }
}
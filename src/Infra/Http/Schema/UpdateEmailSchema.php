<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class UpdateEmailSchema
{
    public function __construct(
        
        #[Constraints\NotBlank(message: "ID is required")]
        #[Constraints\Uuid(message: "Invalid UUID")]
        public $id,

        #[Constraints\NotBlank(message: "Email is required")]
        #[Constraints\Email(message: "Invalid email format")]
        public $email,

        #[Constraints\NotBlank(message: "Email is required")]
        #[Constraints\PasswordStrength(minScore: 1)]
        #[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
        public $password
    ) {
    }
}
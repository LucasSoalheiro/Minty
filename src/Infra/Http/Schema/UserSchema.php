<?php

namespace Src\Infra\Http\Schema;

use Exception;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserSchema
{
    public function __construct(
        #[NotBlank(message: "Name is required")]
        #[Length(min: 3, minMessage: "Name must have at least 3 characters")]
        public $name,

        #[NotBlank(message: "Email is required")]
        #[Email(message: "Invalid email format")]
        public $email,

        #[NotBlank(message: "Password is required")]
        #[Length(min: 6, minMessage: "Password must have at least 6 characters")]
        public $password
    ) {
    }
}
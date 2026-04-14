<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginSchema {
    public function __construct(
        #[NotBlank(message: "Name is required")]
        #[Email(message: "Invalid email format")]
        public $email,

        #[NotBlank(message: "Password is required")]
        public $password,
    )
    {
       
    }
}
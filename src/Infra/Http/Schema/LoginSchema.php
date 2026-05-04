<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class LoginSchema
{
    #[Constraints\NotBlank(message: "Name is required")]
    #[Constraints\Email(message: "Invalid email format")]
    public string $email;

    #[Constraints\NotBlank(message: "Password is required")]
    public string $password;
    public function __construct(
        ?string $email = "",
        ?string $password = ""
    )
    {
       if (empty($email) || empty($password)) {
            throw new ValidatorException("Email and password are required");
        }
        $this->email = $email;
        $this->password = $password;
    }
}
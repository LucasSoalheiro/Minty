<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;


class CreateUserSchema
{
    #[Constraints\NotBlank(message: "Name is required")]
    #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
    public string $name;

    #[Constraints\NotBlank(message: "Email is required")]
    #[Constraints\Email(message: "Invalid email format")]
    public string $email;

    #[Constraints\NotBlank(message: "Password is required")]
    #[Constraints\PasswordStrength(minScore: 1)]
    #[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
    public string $password;
    public function __construct(
        ?string $name = "",
        ?string $email = "",
        ?string $password = ""
    ) {
        if (empty($name) || empty($email) || empty($password)) {
            throw new ValidatorException("Name, email and password are required");
        }
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}
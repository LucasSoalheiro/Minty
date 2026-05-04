<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class UpdateEmailSchema
{
    #[Constraints\NotBlank(message: "ID is required")]
    #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
    public string $id;

    #[Constraints\NotBlank(message: "Email is required")]
    #[Constraints\Email(message: "Invalid email format")]
    public string $email;

    #[Constraints\NotBlank(message: "Password is required")]
    #[Constraints\PasswordStrength(minScore: 1)]
    #[Constraints\Length(min: 6, minMessage: "Password must have at least 6 characters")]
    public string $password;
    public function __construct(
        ?string $id = "",
        ?string $email = "",
        ?string $password = ""
    ) {
        if (empty($id) || empty($email) || empty($password)) {
            throw new ValidatorException("ID, email and password are required");
        }
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }
}

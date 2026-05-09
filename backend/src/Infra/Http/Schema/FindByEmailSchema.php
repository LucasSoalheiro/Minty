<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class FindByEmailSchema
{
    #[Constraints\NotBlank(message: "Email is required")]
    #[Constraints\Email(message: "Invalid email format")]
    public string $email;
    public function __construct(
        ?string $email = ""
    ) {
        if (empty($email)) {
            throw new ValidatorException("Email is required");
        }
        $this->email = $email;
    }
}
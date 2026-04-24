<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class FindByEmailSchema
{
    public function __construct(
        #[Constraints\NotBlank(message: "Email is required")]
        #[Constraints\Email(message: "Invalid email format")]
        public string $email
    )
    {
    }
}
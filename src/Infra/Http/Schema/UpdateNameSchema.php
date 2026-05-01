<?php
namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class UpdateNameSchema
{
    public function __construct(

        #[Constraints\NotBlank(message: "Email is required")]
        #[Constraints\Email(message: "Invalid email format")]
        public string $email,

        #[Constraints\NotBlank(message: "Name is required")]
        #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
        public string $name,
    ) {
    }
}
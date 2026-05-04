<?php
namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class UpdateNameSchema
{
    #[Constraints\NotBlank(message: "Email is required")]
    #[Constraints\Email(message: "Invalid email format")]
    public string $email;

    #[Constraints\NotBlank(message: "Name is required")]
    #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
    public string $name;
    public function __construct(
        ?string $email = "",
        ?string $name = ""
    ) {
        if (empty($email) || empty($name)) {
            throw new ValidatorException("Email and name are required");
        }
        $this->email = $email;
        $this->name = $name;
    }
}
<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class CreateAccountSchema
{
    #[Constraints\NotBlank(message: "Name is required")]
    #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
    public string $name;

    #[Constraints\NotBlank(message: "Balance is required")]
    #[Constraints\PositiveOrZero(message: "Balance must be a positive number or zero")]
    public int $balance;

    #[Constraints\NotBlank(message: "User ID is required")]
    #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
    public string $userId;
    public function __construct(
        ?string $name = "",
        ?int $balance = 0,
        ?string $userId = ""
    ) {
        if (empty($name) || empty($userId)) {
            throw new ValidatorException("Name and User ID are required");
        }
        $this->name = $name;
        $this->balance = $balance;
        $this->userId = $userId;
    }
}
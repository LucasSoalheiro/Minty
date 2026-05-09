<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class UpdateAccountSchema
{
    #[Constraints\NotBlank(message: "Name is required")]
    #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
    public string $name;

    #[Constraints\NotBlank(message: "Account ID is required")]
    #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
    public string $accountId;

    public function __construct(
        ?string $name = "",
        ?string $accountId = ""
    ) {
        if (empty($name) || empty($accountId)) {
            throw new ValidatorException("Name and Account ID are required");
        }
        $this->name = $name;
        $this->accountId = $accountId;
    }
}
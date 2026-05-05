<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class TransferSchema
{
    #[Constraints\NotBlank(message: "Account ID is required")]
    #[Constraints\Uuid(message: "Invalid Account ID format", versions: [4])]
    public string $fromAccountId;

    #[Constraints\NotBlank(message: "the receiver Account ID is required")]
    #[Constraints\Uuid(message: "Invalid Account ID format", versions: [4])]
    public string $toAccountId;

    #[Constraints\NotBlank(message: "Amount is required")]
    #[Constraints\Type(type: "numeric", message: "Amount must be a number")]
    public int $amount;

    #[Constraints\NotBlank(message: "Category ID is required")]
    #[Constraints\Uuid(message: "Invalid Category ID format", versions: [4])]
    public string $categoryId;

    #[Constraints\Length(max: 255, maxMessage: "Description cannot be longer than 255 characters")]
    public ?string $description;

    public function __construct(
        ?string $fromAccountId = "",
        ?string $toAccountId = "",
        ?int $amount = 0,
        ?string $categoryId = "",
        ?string $description = null
    ) {
        if (empty($fromAccountId) || empty($toAccountId) || empty($categoryId)) {
            throw new ValidatorException("fromAccount ID, toAccount ID and Category ID are required");
        }
        $this->fromAccountId = $fromAccountId;
        $this->toAccountId = $toAccountId;
        $this->amount = $amount;
        $this->categoryId = $categoryId;
        $this->description = $description;
    }
}
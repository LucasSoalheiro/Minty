<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class UpdateCategorySchema
{
    #[Constraints\NotBlank(message: "ID is required")]
    #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
    public string $id;
    #[Constraints\Length(max: 255, maxMessage: "Name cannot be longer than {{ limit }} characters")]
    public ?string $name;
    #[Constraints\Length(max: 255, maxMessage: "Description cannot be longer than {{ limit }} characters")]
    public ?string $description;
    public function __construct(
        ?string $id = "",
        ?string $name = null,
        ?string $description = null
    ) {
        if (empty($id)) {
            throw new ValidatorException("ID is required");
        }
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
}
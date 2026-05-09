<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class FindByIdSchema
{
    #[Constraints\NotBlank(message: "ID is required")]
    #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
    public string $id;
    public function __construct(
        ?string $id = ""
    ) {
        if (empty($id)) {
            throw new ValidatorException("ID is required");
        }
        $this->id = $id;
    }
}
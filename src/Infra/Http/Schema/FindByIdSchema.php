<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class FindByIdSchema
{
    public function __construct(

        #[Constraints\NotBlank(message: "ID is required")]
        #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
        public string $id
    ) {
    }
}
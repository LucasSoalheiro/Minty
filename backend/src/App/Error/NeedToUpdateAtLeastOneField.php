<?php

namespace Src\App\Error;

class NeedToUpdateAtLeastOneField extends ApplicationError
{
    public function __construct($message = "At least one field should be changed")
    {
        parent::__construct($message);
    }
}
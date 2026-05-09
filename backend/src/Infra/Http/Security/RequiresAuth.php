<?php

namespace Src\Infra\Http\Security;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class RequiresAuth
{
}
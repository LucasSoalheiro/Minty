<?php

namespace Src\Infra\Http\Util;

use Src\Infra\Http\Error\InvalidJsonBody;
use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(Request $request, string $schemaClass, string $dtoClass, array $extra = []): object
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            throw new InvalidJsonBody();
        }

        $payload = new $schemaClass(...array_merge($data, $extra));

        $errors = $this->validator->validate($payload);

        if (\count($errors) > 0) {
            throw new ValidatorException($errors);
        }
        $dto = new $dtoClass(...get_object_vars($payload));
        return $dto;
    }
}


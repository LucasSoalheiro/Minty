<?php

namespace Src\Infra\Http\Response;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class Response
{
    public static function jsonResponse(int $status, ?string $message = "", ?array $data = [])
    {
        return new HttpFoundationResponse(json_encode([
            "statusType" => "success",
            "message" => $message,
            "data" => $data
        ]), $status, ["Content-type" => "application/json"]);

    }
}
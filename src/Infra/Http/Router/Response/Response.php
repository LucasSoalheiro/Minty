<?php

namespace Src\Infra\Http\Router\Response;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

enum StatusType
{
    case Success;
    case Error;
}
class Response
{
    private static function jsonResponse(StatusType $statusType, int $status, ?string $message = "", ?array $data = [])
    {
        return new HttpFoundationResponse(json_encode([
            "statusType" => $statusType->name,
            "message" => $message,
            "data" => $data
        ]), $status, ["Content-type" => "application/json"]);
    }

    public static function OK(?string $message = "Ok Success", ?array $data = [])
    {
        return self::jsonResponse(StatusType::Success, 200, $message, $data);
    }
    public static function Created(?string $message = "Created Success", ?array $data = [])
    {
        return self::jsonResponse(StatusType::Success, 201, $message, $data);
    }
    public static function NoContent()
    {
        return self::jsonResponse(StatusType::Success, 201);
    }
    public static function BadRequest(?string $message = "Bad request Error",)
    {
        return self::jsonResponse(StatusType::Error, 400, $message);
    }
    public static function Unauthorized(?string $message = "Unauthorized Error",)
    {
        return self::jsonResponse(StatusType::Error, 401, $message);
    }
    public static function Forbidden(?string $message = "Forbidden Error",)
    {
        return self::jsonResponse(StatusType::Error, 403, $message);
    }
    public static function NotFound(?string $message = "Not Found Error",)
    {
        return self::jsonResponse(StatusType::Error, 404, $message);
    }
    public static function Conflict(?string $message = "Conflict Error",)
    {
        return self::jsonResponse(StatusType::Error, 409, $message);
    }
    public static function InternalServerError(?string $message = "Internal Server Error",)
    {
        return self::jsonResponse(StatusType::Error, 500, $message);
    }
}
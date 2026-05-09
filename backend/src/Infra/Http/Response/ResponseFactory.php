<?php

namespace Src\Infra\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFactory
{
    public static function success(mixed $data, ?string $message, int $status = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], $status);
    }

    public static function created(mixed $data, ?string $message, ): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], 201);
    }

    public static function noContent(): JsonResponse
    {
        return new JsonResponse(null, 204);
    }
}
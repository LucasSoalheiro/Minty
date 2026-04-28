<?php

namespace Src\Infra\Http\EventListener;

use ReflectionMethod;
use Src\App\Security\TokenService;
use Src\Infra\Http\Security\RequiresAuth;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[AsEventListener(event: 'kernel.controller')]
class AuthListener
{
    public function __construct(private TokenService $tokenService)
    {
    }
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!\is_array($controller)) {
            return;
        }

        [$controllerInstance, $method] = $controller;

        $reflection = new ReflectionMethod($controllerInstance, $method);

        $attributes = $reflection->getAttributes(RequiresAuth::class);

        if (empty($attributes)) {
            return; 
        }
        $request = $event->getRequest();

        $token = $request->headers->get('Authorization');

        if ($token === null) {
            throw new UnauthorizedHttpException("Bearer", "Token Null");
        }

        $token = substr($token, 7);
        try {
            $payload = $this->tokenService->validateToken($token);
            $request->attributes->set('user_id', $payload->userId);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException("Bearer", "Error validating token: " . $e->getMessage());
        }
    }
}
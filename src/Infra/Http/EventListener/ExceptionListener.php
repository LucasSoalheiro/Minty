<?php

namespace Src\Infra\Http\EventListener;

use Src\App\Error\AccountNotFound;
use Src\App\Error\CategoryAlreadyInactive;
use Src\App\Error\CategoryInactive;
use Src\App\Error\CategoryNotFound;
use Src\App\Error\EmailAlreadyInUse;
use Src\App\Error\EmailNotFound;
use Src\App\Error\InvalidRefreshToken;
use Src\App\Error\NeedToUpdateAtLeastOneField;
use Src\App\Error\SessionNotFound;
use Src\App\Error\UserNotFound;
use Src\App\Error\WrongPassword;
use Src\Domain\Error\AccountAlreadyDeactivated;
use Src\Domain\Error\CategoryInactive as ErrorCategoryInactive;
use Src\Domain\Error\EmailShouldBeDifferent;
use Src\Domain\Error\InsufficientFunds;
use Src\Domain\Error\InvalidAmount;
use Src\Domain\Error\InvalidCreatedAt;
use Src\Domain\Error\InvalidDescription;
use Src\Domain\Error\InvalidEmail;
use Src\Domain\Error\InvalidInitialBalance;
use Src\Domain\Error\InvalidPassword;
use Src\Domain\Error\InvalidSession;
use Src\Domain\Error\InvalidTransfer;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\Error\NameShouldBeDifferent;
use Src\Domain\Error\PasswordDoesNotMatch;
use Src\Domain\Error\TransactionAlreadyCancelled;
use Src\Domain\Error\UnformattedPassword;
use Src\Domain\Error\WeakPassword;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener]
final class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message = \sprintf(
            'Error: %s\nCode: %s',
            $exception->getMessage(),
            $exception->getCode()
        );
        $statusCode = match (true) {
            $exception instanceof \InvalidArgumentException => 400,
            $exception instanceof EmailAlreadyInUse => 409,
            $exception instanceof AccountNotFound => 404,
            $exception instanceof CategoryAlreadyInactive => 409,
            $exception instanceof CategoryNotFound => 404,
            $exception instanceof EmailNotFound => 404,
            $exception instanceof InvalidRefreshToken => 400,
            $exception instanceof NeedToUpdateAtLeastOneField => 400,
            $exception instanceof SessionNotFound => 404,
            $exception instanceof UserNotFound => 404,
            $exception instanceof WrongPassword => 401,
            $exception instanceof AccountAlreadyDeactivated => 409,
            $exception instanceof CategoryInactive => 409,
            $exception instanceof ErrorCategoryInactive => 409,
            $exception instanceof EmailShouldBeDifferent => 409,
            $exception instanceof InsufficientFunds => 422,
            $exception instanceof InvalidAmount => 400,
            $exception instanceof InvalidCreatedAt => 500,
            $exception instanceof InvalidDescription => 400,
            $exception instanceof InvalidEmail => 400,
            $exception instanceof InvalidInitialBalance => 400,
            $exception instanceof InvalidPassword => 400,
            $exception instanceof InvalidSession => 400,
            $exception instanceof InvalidTransfer => 400,
            $exception instanceof NameCannotBeNull => 400,
            $exception instanceof NameShouldBeDifferent => 409,
            $exception instanceof PasswordDoesNotMatch => 403,
            $exception instanceof TransactionAlreadyCancelled => 409,
            $exception instanceof UnformattedPassword => 422,
            $exception instanceof WeakPassword => 422,
        };

        $response = new JsonResponse([
            'error' => true,
            'message' => $message
        ], $statusCode);

        $event->setResponse($response);
    }
}
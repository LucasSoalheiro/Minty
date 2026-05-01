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
use Src\Domain\Error\ConflictPassword;
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
use Src\Infra\Http\Error\CookieException;
use Src\Infra\Http\Error\InvalidJsonBody;
use Src\Infra\Http\Error\ParamsException;
use Src\Infra\Http\Error\QueryException;
use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException as ValidatorInvalidArgumentException;

#[AsEventListener]
final class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse([
                'error' => true,
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ], $exception->getStatusCode());

            $event->setResponse($response);
            return;
        }
        [$statusCode, $errorCode] = match (true) {
            $exception instanceof \InvalidArgumentException => [400, 'INVALID_ARGUMENT'],
            $exception instanceof ValidatorInvalidArgumentException => [400, 'VALIDATION_ERROR'],
            $exception instanceof CookieException => [400, 'COOKIE_ERROR'],
            $exception instanceof InvalidJsonBody => [400, 'INVALID_JSON_BODY'],
            $exception instanceof ValidatorException => [400, 'VALIDATION_ERROR'],
            $exception instanceof ParamsException => [400, 'PARAMS_ERROR'],
            $exception instanceof QueryException => [400, 'QUERY_ERROR'],
            $exception instanceof EmailAlreadyInUse => [409, 'EMAIL_ALREADY_IN_USE'],
            $exception instanceof ConflictPassword => [409, 'CONFLICT_PASSWORD'],
            $exception instanceof AccountNotFound => [404, 'ACCOUNT_NOT_FOUND'],
            $exception instanceof CategoryAlreadyInactive => [409, 'CATEGORY_ALREADY_INACTIVE'],
            $exception instanceof CategoryNotFound => [404, 'CATEGORY_NOT_FOUND'],
            $exception instanceof EmailNotFound => [404, 'EMAIL_NOT_FOUND'],
            $exception instanceof InvalidRefreshToken => [400, 'INVALID_REFRESH_TOKEN'],
            $exception instanceof NeedToUpdateAtLeastOneField => [400, 'NEED_TO_UPDATE_AT_LEAST_ONE_FIELD'],
            $exception instanceof SessionNotFound => [404, 'SESSION_NOT_FOUND'],
            $exception instanceof UserNotFound => [404, 'USER_NOT_FOUND'],
            $exception instanceof WrongPassword => [401, 'WRONG_PASSWORD'],
            $exception instanceof AccountAlreadyDeactivated => [409, 'ACCOUNT_ALREADY_DEACTIVATED'],
            $exception instanceof CategoryInactive || $exception instanceof ErrorCategoryInactive => [409, 'CATEGORY_INACTIVE'],
            $exception instanceof EmailShouldBeDifferent => [409, 'EMAIL_SHOULD_BE_DIFFERENT'],
            $exception instanceof InsufficientFunds => [422, 'INSUFFICIENT_FUNDS'],
            $exception instanceof InvalidAmount => [400, 'INVALID_AMOUNT'],
            $exception instanceof InvalidCreatedAt => [500, 'INVALID_CREATED_AT'],
            $exception instanceof InvalidDescription => [400, 'INVALID_DESCRIPTION'],
            $exception instanceof InvalidEmail => [400, 'INVALID_EMAIL'],
            $exception instanceof InvalidInitialBalance => [400, 'INVALID_INITIAL_BALANCE'],
            $exception instanceof InvalidPassword => [400, 'INVALID_PASSWORD'],
            $exception instanceof InvalidSession => [400, 'INVALID_SESSION'],
            $exception instanceof InvalidTransfer => [400, 'INVALID_TRANSFER'],
            $exception instanceof NameCannotBeNull => [400, 'NAME_CANNOT_BE_NULL'],
            $exception instanceof NameShouldBeDifferent => [409, 'NAME_SHOULD_BE_DIFFERENT'],
            $exception instanceof PasswordDoesNotMatch => [403, 'PASSWORD_DOES_NOT_MATCH'],
            $exception instanceof TransactionAlreadyCancelled => [409, 'TRANSACTION_ALREADY_CANCELLED'],
            $exception instanceof UnformattedPassword => [422, 'UNFORMATTED_PASSWORD'],
            $exception instanceof WeakPassword => [422, 'WEAK_PASSWORD'],
            default => [500, 'INTERNAL_SERVER_ERROR'],
        };

        $response = new JsonResponse([
            'error' => true,
            'code' => $errorCode,
            'message' => $exception->getMessage()
        ], $statusCode);

        $event->setResponse($response);
    }
}
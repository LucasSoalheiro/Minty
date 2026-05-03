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
use Psr\Log\LoggerInterface;

#[AsEventListener]
final class ExceptionListener
{
    private const array EXCEPTION_MAP = [
        CookieException::class => [400, 'COOKIE_ERROR'],
        InvalidJsonBody::class => [400, 'INVALID_JSON_BODY'],
        ValidatorException::class => [400, 'VALIDATION_ERROR'],
        ParamsException::class => [400, 'PARAMS_ERROR'],
        QueryException::class => [400, 'QUERY_ERROR'],
        InvalidRefreshToken::class => [400, 'INVALID_REFRESH_TOKEN'],
        NeedToUpdateAtLeastOneField::class => [400, 'NEED_TO_UPDATE_AT_LEAST_ONE_FIELD'],
        InvalidAmount::class => [400, 'INVALID_AMOUNT'],
        InvalidDescription::class => [400, 'INVALID_DESCRIPTION'],
        InvalidEmail::class => [400, 'INVALID_EMAIL'],
        InvalidInitialBalance::class => [400, 'INVALID_INITIAL_BALANCE'],
        InvalidPassword::class => [400, 'INVALID_PASSWORD'],
        InvalidSession::class => [400, 'INVALID_SESSION'],
        InvalidTransfer::class => [400, 'INVALID_TRANSFER'],
        NameCannotBeNull::class => [400, 'NAME_CANNOT_BE_NULL'],
        WrongPassword::class => [401, 'WRONG_PASSWORD'],
        PasswordDoesNotMatch::class => [403, 'PASSWORD_DOES_NOT_MATCH'],
        AccountNotFound::class => [404, 'ACCOUNT_NOT_FOUND'],
        CategoryNotFound::class => [404, 'CATEGORY_NOT_FOUND'],
        EmailNotFound::class => [404, 'EMAIL_NOT_FOUND'],
        SessionNotFound::class => [404, 'SESSION_NOT_FOUND'],
        UserNotFound::class => [404, 'USER_NOT_FOUND'],
        EmailAlreadyInUse::class => [409, 'EMAIL_ALREADY_IN_USE'],
        ConflictPassword::class => [409, 'CONFLICT_PASSWORD'],
        CategoryAlreadyInactive::class => [409, 'CATEGORY_ALREADY_INACTIVE'],
        CategoryInactive::class => [409, 'CATEGORY_INACTIVE'],
        ErrorCategoryInactive::class => [409, 'CATEGORY_INACTIVE'],
        EmailShouldBeDifferent::class => [409, 'EMAIL_SHOULD_BE_DIFFERENT'],
        AccountAlreadyDeactivated::class => [409, 'ACCOUNT_ALREADY_DEACTIVATED'],
        NameShouldBeDifferent::class => [409, 'NAME_SHOULD_BE_DIFFERENT'],
        TransactionAlreadyCancelled::class => [409, 'TRANSACTION_ALREADY_CANCELLED'],
        InsufficientFunds::class => [422, 'INSUFFICIENT_FUNDS'],
        UnformattedPassword::class => [422, 'UNFORMATTED_PASSWORD'],
        WeakPassword::class => [422, 'WEAK_PASSWORD'],
        InvalidCreatedAt::class => [500, 'INVALID_CREATED_AT'],
    ];

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $this->respond($event, $exception->getStatusCode(), $exception->getMessage());
            return;
        }

        [$status, $code] = $this->resolve($exception);

        if ($status >= 500) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }

        $this->respond($event, $status, $this->formatMessage($exception), $code);
    }

    private function resolve(\Throwable $exception): array
    {
        foreach (self::EXCEPTION_MAP as $class => $mapping) {
            if ($exception instanceof $class) {
                return $mapping;
            }
        }

        return [500, 'INTERNAL_SERVER_ERROR'];
    }

    private function formatMessage(\Throwable $exception): string
    {
        if (!$exception instanceof ValidatorInvalidArgumentException) {
            return $exception->getMessage();
        }

        $message = preg_replace('/^Object\(.*?\)\..*?:[\s\n]+/', '', $exception->getMessage());
        return preg_replace('/\s*\(code.*\)\s*/', '', $message);
    }

    private function respond(ExceptionEvent $event, int $status, string $message, string $code = ''): void
    {
        $body = ['error' => true, 'message' => $message];

        if ($code !== '') {
            $body['code'] = $code;
        }

        $event->setResponse(new JsonResponse($body, $status));
    }
}
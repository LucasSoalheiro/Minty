<?php
namespace Src\Domain\Entities;

use Src\Domain\Error\InvalidCreatedAt;
use Src\Domain\Error\TransactionAlreadyCancelled;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;

enum TransactionEnum
{
    case INFLOW;
    case OUTFLOW;
}

enum TransactionStatusEnum
{
    case CANCELLED;
    case PENDING;
    case DONE;
}

final class Transaction
{
    private function __construct(
        public readonly UUID $id,
        public readonly UUID $accountId,
        public readonly Money $amount,
        public readonly \DateTime $createdAt,
        public readonly TransactionEnum $type,
        public private(set) TransactionStatusEnum $status,
        public readonly ?string $description,
        public readonly UUID $categoryId,

    ) {
    }

    public static function create(
        UUID $accountId,
        Money $amount,
        TransactionEnum $type,
        ?string $description,
        UUID $categoryId
    ): Transaction {
        $date = new \DateTime();

        return new Transaction(
            UUID::generate(),
            $accountId,
            $amount,
            $date,
            $type,
            TransactionStatusEnum::PENDING,
            $description,
            $categoryId
        );
    }

    public static function restore(
        UUID $id,
        UUID $accountId,
        Money $amount,
        TransactionEnum $type,
        TransactionStatusEnum $status,
        ?string $description,
        UUID $categoryId,
        \DateTime $createdAt
    ): Transaction {
        if ($createdAt > new \DateTime()) {
            throw new InvalidCreatedAt();
        }
        return new Transaction(
            $id,
            $accountId,
            $amount,
            $createdAt,
            $type,
            $status,
            $description,
            $categoryId
        );
    }

    public function cancel(): void
    {
        if ($this->status === TransactionStatusEnum::CANCELLED) {
            throw new TransactionAlreadyCancelled();
        }
        $this->status = TransactionStatusEnum::CANCELLED;
    }

}
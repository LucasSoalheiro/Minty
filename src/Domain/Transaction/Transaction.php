<?php
namespace Src\Domain\Transaction;

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
    case DONE;
}

class Transaction
{
    private function __construct(
        private readonly UUID $id,
        private readonly UUID $accountId,
        private readonly Money $amount,
        private readonly \DateTime $createdAt,
        private readonly TransactionEnum $type,
        private TransactionStatusEnum $status,
        private readonly ?string $description,
        private readonly UUID $categoryId,
    ) {
    }

    public static function create(UUID $accountId, Money $amount, TransactionEnum $type, ?string $description, UUID $categoryId): Transaction
    {
        $date = new \DateTime();

        return new Transaction(UUID::generate(), $accountId, $amount, $date, $type, TransactionStatusEnum::DONE, $description, $categoryId);
    }

    public static function restore(UUID $id, UUID $accountId, Money $amount, TransactionEnum $type, TransactionStatusEnum $status, ?string $description, UUID $categoryId, \DateTime $createdAt): Transaction
    {
        if ($createdAt > new \DateTime()) {
            throw new InvalidCreatedAt();
        }
        return new Transaction($id, $accountId, $amount, $createdAt, $type, $status, $description, $categoryId);
    }

    public function getId(): UUID
    {
        return $this->id;
    }
    public function getAccountId(): UUID
    {
        return $this->accountId;
    }
    public function getAmount(): int
    {
        return $this->amount->value();
    }
    public function getType(): TransactionEnum
    {
        return $this->type;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getStatus(): TransactionStatusEnum
    {
        return $this->status;
    }
    public function getCategoryId(): UUID
    {
        return $this->categoryId;
    }

    public function cancel(): void
    {
        if ($this->status === TransactionStatusEnum::CANCELLED) {
            throw new TransactionAlreadyCancelled();
        }
        $this->status = TransactionStatusEnum::CANCELLED;
    }

}
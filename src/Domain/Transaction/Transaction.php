<?php
namespace Src\Domain\Transaction;

use Src\Domain\shared\Money;
use Src\Domain\shared\UUID;
use Src\Domain\Transaction\error\InvalidAmount;
use Src\Domain\Transaction\error\InvalidCreatedAt;
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
    private readonly UUID $id;
    private function __construct(
        private readonly string $accountId,
        private readonly Money $amount,
        private readonly \DateTime $createdAt,
        private readonly TransactionEnum $type,
        private readonly TransactionStatusEnum $status,
        private readonly ?string $description,
        private readonly string $categoryId,
    ) {
        $this->id = UUID::generate();
    }

    public static function create(string $accountId, Money $amount, TransactionEnum $type, ?string $description, string $categoryId): Transaction
    {
        $date = new \DateTime();
        UUID::fromString($accountId); // Validate accountId is a valid UUID
        UUID::fromString($categoryId); // Validate categoryId is a valid UUID
        if ($amount->value() <= 0) {
            throw new InvalidAmount();
        }
        return new Transaction($accountId, $amount, $date, $type, TransactionStatusEnum::DONE, $description, $categoryId);
    }

    public static function restore(string $accountId, Money $amount, TransactionEnum $type, TransactionStatusEnum $status, string $description, string $categoryId, \DateTime $createdAt): Transaction
    {
        UUID::fromString($accountId); 
        UUID::fromString($categoryId);
        if ($amount->value() <= 0) {
            throw new InvalidAmount();
        }
        if ($createdAt > new \DateTime()) {
            throw new InvalidCreatedAt();
        }
        return new Transaction($accountId, $amount, $createdAt, $type, $status, $description, $categoryId);
    }

    public function getId(): UUID
    {
        return $this->id;
    }
    public function getAccountId(): string
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
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function cancel(): void
    {
        if ($this->status === TransactionStatusEnum::CANCELLED) {
            throw new \ErrorException("Transaction is already cancelled.");
        }
        $this->status = TransactionStatusEnum::CANCELLED;
    }

}
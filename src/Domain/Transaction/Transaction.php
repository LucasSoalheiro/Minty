<?php
namespace Src\Domain\Transaction;

use Src\Domain\shared\Money;
use Src\Domain\shared\UUID;
use Src\Domain\Transaction\error\InvalidAmount;
use Src\Domain\Transaction\error\InvalidCreatedAt;
use Src\Domain\Transaction\error\TransactionAlreadyCancelled;
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
        private readonly UUID $accountId,
        private readonly Money $amount,
        private readonly \DateTime $createdAt,
        private readonly TransactionEnum $type,
        private TransactionStatusEnum $status,
        private readonly ?string $description,
        private readonly UUID $categoryId,
    ) {
        $this->id = UUID::generate();
    }

    public static function create(UUID $accountId, Money $amount, TransactionEnum $type, ?string $description, UUID $categoryId): Transaction
    {
        $date = new \DateTime();
        UUID::fromString($accountId); // Validate accountId is a valid UUID
        UUID::fromString($categoryId); // Validate categoryId is a valid UUID
        if ($amount->value() <= 0) {
            throw new InvalidAmount();
        }
        return new Transaction($accountId, $amount, $date, $type, TransactionStatusEnum::DONE, $description, $categoryId);
    }

    public static function restore(UUID $accountId, Money $amount, TransactionEnum $type, TransactionStatusEnum $status, ?string $description, UUID $categoryId, \DateTime $createdAt): Transaction
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
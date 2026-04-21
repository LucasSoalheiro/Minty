<?php
namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Src\Domain\Entities\Account;
use Src\Domain\ValueObject\Money;
use Src\Domain\ValueObject\UUID;
use Src\Domain\Error\AccountAlreadyDeactivated;
use Src\Domain\Error\InsufficientFunds;
use Src\Domain\Error\InvalidAmount;
use Src\Domain\Error\InvalidTransfer;
use Src\Domain\Error\NameCannotBeNull;

class AccountTest extends TestCase
{
    private function makeUUID(): UUID
    {
        return UUID::generate();
    }

    public function testCreateValidAccount()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $this->assertTrue($account->isActive);
        $this->assertEquals(100, $account->balance->value());
        $this->assertEquals("John Doe", $account->name);
    }

    public function testCreateAccountEmptyNameThrows()
    {
        $this->expectException(NameCannotBeNull::class);
        Account::create("", Money::create(100), $this->makeUUID());
    }

    public function testCreateAccountNegativeBalanceThrows()
    {
        $this->expectException(InvalidAmount::class);
        Account::create("John Doe", Money::create(-10), $this->makeUUID());
    }

    public function testDepositIncreasesBalance()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $account->deposit(Money::create(50));
        $this->assertEquals(150, $account->balance->value());
    }

    public function testDepositZeroOrNegativeThrows()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $this->expectException(InvalidAmount::class);
        $account->deposit(Money::create(0));
    }

    public function testWithdrawDecreasesBalance()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $account->withdraw(Money::create(40));
        $this->assertEquals(60, $account->balance->value());
    }

    public function testWithdrawMoreThanBalanceThrows()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $this->expectException(InsufficientFunds::class);
        $account->withdraw(Money::create(150));
    }

    public function testWithdrawNegativeThrows()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $this->expectException(InvalidAmount::class);
        $account->withdraw(Money::create(-1));
    }

    public function testTransferUpdatesBothAccounts()
    {
        $account1 = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $account2 = Account::create("Jane Doe", Money::create(50), $this->makeUUID());

        $account1->transfer($account2, Money::create(70));

        $this->assertEquals(30, $account1->balance->value());
        $this->assertEquals(120, $account2->balance->value());
    }

    public function testTransferToSelfThrows()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $this->expectException(InvalidTransfer::class);
        $account->transfer($account, Money::create(50));
    }

    public function testTransferMoreThanBalanceThrows()
    {
        $account1 = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $account2 = Account::create("Jane Doe", Money::create(50), $this->makeUUID());
        $this->expectException(InsufficientFunds::class);
        $account1->transfer($account2, Money::create(150));
    }

    public function testDeactivateSetsInactive()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $account->deactivate();
        $this->assertFalse($account->isActive);
    }

    public function testOperationsAfterDeactivateThrow()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $account->deactivate();

        $this->expectException(AccountAlreadyDeactivated::class);
        $account->deposit(Money::create(10));
    }

    public function testDeactivateTwiceThrows()
    {
        $account = Account::create("John Doe", Money::create(100), $this->makeUUID());
        $account->deactivate();
        $this->expectException(AccountAlreadyDeactivated::class);
        $account->deactivate();
    }
}
<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Src\Domain\Entities\Session;
use Src\Domain\ValueObject\UUID;

class SessionTest extends TestCase
{
    public function testCreateSession(): void
    {
        $token = bin2hex(random_bytes(32));
        $session = Session::create(UUID::generate(),$token);

        $this->assertInstanceOf(Session::class, $session);
        $this->assertEquals(true, $session->matches($token));
        $this->assertEquals(true, $session->isValid());
    }
}
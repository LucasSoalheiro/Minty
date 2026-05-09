<?php

namespace Src\Infra\Db\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Session")]
class SessionEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $userId;

    #[ORM\Column(type: 'string')]
    private string $tokenHash;

    #[ORM\Column(type: 'datetime')]
    private DateTime $expiresAt;

    #[ORM\Column(type: 'boolean')]
    private bool $revoked;

    public function getId()
    {
        return $this->id;
    }
    public function setId(string $id)
    {
        $this->id  = $id;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }
    public function getTokenHash()
    {
        return $this->tokenHash;
    }
    public function setTokenHash(string $tokenHash)
    {
        $this->tokenHash = $tokenHash;
    }
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
    public function setExpiresAt(DateTime $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }
    public function getRevoked()
    {
        return $this->revoked;
    }
    public function setRevoked(bool $revoked)
    {
        $this->revoked = $revoked;
    }
}
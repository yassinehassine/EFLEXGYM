<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tokens
 *
 * @ORM\Table(name="tokens")
 * @ORM\Entity
 */
class Tokens
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="string", length=255, nullable=false)
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="reset_token", type="string", length=255, nullable=false)
     */
    private $resetToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $expirationTime = 'CURRENT_TIMESTAMP';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(string $resetToken): static
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getExpirationTime(): ?\DateTimeInterface
    {
        return $this->expirationTime;
    }

    public function setExpirationTime(\DateTimeInterface $expirationTime): static
    {
        $this->expirationTime = $expirationTime;

        return $this;
    }


}

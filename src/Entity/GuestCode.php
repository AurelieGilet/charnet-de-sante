<?php

namespace App\Entity;

use App\Repository\GuestCodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuestCodeRepository::class)
 */
class GuestCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="guestCode", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Guest::class, inversedBy="guestCode", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $guest;

    /**
     * @ORM\OneToOne(targetEntity=Cat::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $validity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUsed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    public function setGuest(Guest $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getCat(): ?Cat
    {
        return $this->cat;
    }

    public function setCat(Cat $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getValidity(): ?\DateTimeInterface
    {
        return $this->validity;
    }

    public function setValidity(\DateTimeInterface $validity): self
    {
        $this->validity = $validity;

        return $this;
    }

    public function getIsUsed(): ?bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): self
    {
        $this->isUsed = $isUsed;

        return $this;
    }
}

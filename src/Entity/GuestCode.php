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
    private $User;

    /**
     * @ORM\OneToOne(targetEntity=Guest::class, inversedBy="guestCode", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Guest;

    /**
     * @ORM\OneToOne(targetEntity=Cat::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Cat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Code;

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
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getGuest(): ?Guest
    {
        return $this->Guest;
    }

    public function setGuest(Guest $Guest): self
    {
        $this->Guest = $Guest;

        return $this;
    }

    public function getCat(): ?Cat
    {
        return $this->Cat;
    }

    public function setCat(Cat $Cat): self
    {
        $this->Cat = $Cat;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

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

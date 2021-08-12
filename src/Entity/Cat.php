<?php

namespace App\Entity;

use App\Repository\CatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatRepository::class)
 */
class Cat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $race;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSterilized;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $microchip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tattoo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ownerName;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="cat", orphanRemoval=true)
     */
    private $ownerAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $veterinaryName;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="cat", orphanRemoval=true)
     */
    private $veterinaryAddress;

    public function __construct()
    {
        $this->ownerAddress = new ArrayCollection();
        $this->veterinaryAddress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getCoat(): ?string
    {
        return $this->coat;
    }

    public function setCoat(?string $coat): self
    {
        $this->coat = $coat;

        return $this;
    }

    public function getIsSterilized(): ?bool
    {
        return $this->isSterilized;
    }

    public function setIsSterilized(?bool $isSterilized): self
    {
        $this->isSterilized = $isSterilized;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getMicrochip(): ?string
    {
        return $this->microchip;
    }

    public function setMicrochip(?string $microchip): self
    {
        $this->microchip = $microchip;

        return $this;
    }

    public function getTattoo(): ?string
    {
        return $this->tattoo;
    }

    public function setTattoo(?string $tattoo): self
    {
        $this->tattoo = $tattoo;

        return $this;
    }

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(?string $ownerName): self
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getOwnerAddress(): Collection
    {
        return $this->ownerAddress;
    }

    public function addOwnerAddress(Address $ownerAddress): self
    {
        if (!$this->ownerAddress->contains($ownerAddress)) {
            $this->ownerAddress[] = $ownerAddress;
            $ownerAddress->setCat($this);
        }

        return $this;
    }

    public function removeOwnerAddress(Address $ownerAddress): self
    {
        if ($this->ownerAddress->removeElement($ownerAddress)) {
            // set the owning side to null (unless already changed)
            if ($ownerAddress->getCat() === $this) {
                $ownerAddress->setCat(null);
            }
        }

        return $this;
    }

    public function getVeterinaryName(): ?string
    {
        return $this->veterinaryName;
    }

    public function setVeterinaryName(?string $veterinaryName): self
    {
        $this->veterinaryName = $veterinaryName;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getVeterinaryAddress(): Collection
    {
        return $this->veterinaryAddress;
    }

    public function addVeterinaryAddress(Address $veterinaryAddress): self
    {
        if (!$this->veterinaryAddress->contains($veterinaryAddress)) {
            $this->veterinaryAddress[] = $veterinaryAddress;
            $veterinaryAddress->setCat($this);
        }

        return $this;
    }

    public function removeVeterinaryAddress(Address $veterinaryAddress): self
    {
        if ($this->veterinaryAddress->removeElement($veterinaryAddress)) {
            // set the owning side to null (unless already changed)
            if ($veterinaryAddress->getCat() === $this) {
                $veterinaryAddress->setCat(null);
            }
        }

        return $this;
    }
}

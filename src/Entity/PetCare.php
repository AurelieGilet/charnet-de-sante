<?php

namespace App\Entity;

use App\Repository\PetCareRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PetCareRepository::class)
 */
class PetCare
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=cat::class, inversedBy="petCares")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cat;

    /**
     * @ORM\Column(type="date")
     * 
     * @Assert\NotBlank(
	 * 	    message="Merci de saisir une date",
	 * )
     */
    private $date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foodType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $foodQuantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foodBrand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grooming;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $claws;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eyesEars;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $teeth;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $litterbox;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCat(): ?cat
    {
        return $this->cat;
    }

    public function setCat(?cat $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getFoodType(): ?string
    {
        return $this->foodType;
    }

    public function setFoodType(?string $foodType): self
    {
        $this->foodType = $foodType;

        return $this;
    }

    public function getFoodQuantity(): ?int
    {
        return $this->foodQuantity;
    }

    public function setFoodQuantity(?int $foodQuantity): self
    {
        $this->foodQuantity = $foodQuantity;

        return $this;
    }

    public function getFoodBrand(): ?string
    {
        return $this->foodBrand;
    }

    public function setFoodBrand(?string $foodBrand): self
    {
        $this->foodBrand = $foodBrand;

        return $this;
    }

    public function getGrooming(): ?string
    {
        return $this->grooming;
    }

    public function setGrooming(?string $grooming): self
    {
        $this->grooming = $grooming;

        return $this;
    }

    public function getClaws(): ?bool
    {
        return $this->claws;
    }

    public function setClaws(?bool $claws): self
    {
        $this->claws = $claws;

        return $this;
    }

    public function getEyesEars(): ?string
    {
        return $this->eyesEars;
    }

    public function setEyesEars(?string $eyesEars): self
    {
        $this->eyesEars = $eyesEars;

        return $this;
    }

    public function getTeeth(): ?string
    {
        return $this->teeth;
    }

    public function setTeeth(?string $teeth): self
    {
        $this->teeth = $teeth;

        return $this;
    }

    public function getLitterbox(): ?bool
    {
        return $this->litterbox;
    }

    public function setLitterbox(?bool $litterbox): self
    {
        $this->litterbox = $litterbox;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}

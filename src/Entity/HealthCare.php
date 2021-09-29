<?php

namespace App\Entity;

use App\Repository\HealthCareRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HealthCareRepository::class)
 */
class HealthCare
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cat::class, inversedBy="healthCares")
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
    private $vaccine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $injectionSite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dewormer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $parasite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $treatment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dosage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descaling;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCat(): ?Cat
    {
        return $this->cat;
    }

    public function setCat(?Cat $cat): self
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

    public function getVaccine(): ?string
    {
        return $this->vaccine;
    }

    public function setVaccine(?string $vaccine): self
    {
        $this->vaccine = $vaccine;

        return $this;
    }

    public function getInjectionSite(): ?string
    {
        return $this->injectionSite;
    }

    public function setInjectionSite(?string $injectionSite): self
    {
        $this->injectionSite = $injectionSite;

        return $this;
    }

    public function getDewormer(): ?bool
    {
        return $this->dewormer;
    }

    public function setDewormer(?bool $dewormer): self
    {
        $this->dewormer = $dewormer;

        return $this;
    }

    public function getParasite(): ?string
    {
        return $this->parasite;
    }

    public function setParasite(?string $parasite): self
    {
        $this->parasite = $parasite;

        return $this;
    }

    public function getTreatment(): ?string
    {
        return $this->treatment;
    }

    public function setTreatment(?string $treatment): self
    {
        $this->treatment = $treatment;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(?string $dosage): self
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getDescaling(): ?string
    {
        return $this->descaling;
    }

    public function setDescaling(?string $descaling): self
    {
        $this->descaling = $descaling;

        return $this;
    }
}

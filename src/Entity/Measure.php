<?php

namespace App\Entity;

use App\Repository\MeasureRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeasureRepository::class)
 */
class Measure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cat::class, inversedBy="measures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cat;

    /**
     * @ORM\Column(type="date", nullable=false)
     * 
     * @Assert\NotBlank(
	 * 		message="Merci de saisir une date",
	 * )
     */
    private $date;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $temperature;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isInHeat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPregnant;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $heatEndDate;

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

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(?string $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getIsInHeat(): ?bool
    {
        return $this->isInHeat;
    }

    public function setIsInHeat(?bool $isInHeat): self
    {
        $this->isInHeat = $isInHeat;

        return $this;
    }

    public function getIsMated(): ?bool
    {
        return $this->isMated;
    }

    public function setIsMated(?bool $isMated): self
    {
        $this->isMated = $isMated;

        return $this;
    }

    public function getIsPregnant(): ?bool
    {
        return $this->isPregnant;
    }

    public function setIsPregnant(?bool $isPregnant): self
    {
        $this->isPregnant = $isPregnant;

        return $this;
    }

    public function getHeatEndDate(): ?\DateTimeInterface
    {
        return $this->heatEndDate;
    }

    public function setHeatEndDate(?\DateTimeInterface $heatEndDate): self
    {
        $this->heatEndDate = $heatEndDate;

        return $this;
    }
}

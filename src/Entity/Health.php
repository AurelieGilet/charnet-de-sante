<?php

namespace App\Entity;

use App\Repository\HealthRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HealthRepository::class)
 */
class Health
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cat::class, inversedBy="healths")
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
    private $vetVisitMotif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $allergy;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $disease;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wound;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surgery;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $analysis;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $documentName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document;

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

    public function getVetVisitMotif(): ?string
    {
        return $this->vetVisitMotif;
    }

    public function setVetVisitMotif(?string $vetVisitMotif): self
    {
        $this->vetVisitMotif = $vetVisitMotif;

        return $this;
    }

    public function getAllergy(): ?string
    {
        return $this->allergy;
    }

    public function setAllergy(?string $allergy): self
    {
        $this->allergy = $allergy;

        return $this;
    }

    public function getDisease(): ?string
    {
        return $this->disease;
    }

    public function setDisease(?string $disease): self
    {
        $this->disease = $disease;

        return $this;
    }

    public function getWound(): ?string
    {
        return $this->wound;
    }

    public function setWound(?string $wound): self
    {
        $this->wound = $wound;

        return $this;
    }

    public function getSurgery(): ?string
    {
        return $this->surgery;
    }

    public function setSurgery(?string $surgery): self
    {
        $this->surgery = $surgery;

        return $this;
    }

    public function getAnalysis(): ?string
    {
        return $this->analysis;
    }

    public function setAnalysis(?string $analysis): self
    {
        $this->analysis = $analysis;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }

    public function setDocumentName(?string $documentName): self
    {
        $this->documentName = $documentName;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }
}

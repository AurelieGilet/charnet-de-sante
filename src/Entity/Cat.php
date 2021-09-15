<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
     * 
     * @Assert\NotBlank(
	 * 		message="Merci de saisir le nom de votre chat",
	 * )
     * 
     * @Assert\Length(
	 * 		min="1", 
	 * 		minMessage="Le nom de votre chat doit faire au moins 1 caractère",
     *      max="20",
     *      maxMessage="Le nom de votre chat doit faire moins de 20 caractères"
	 * )
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateOfDeath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Regex(
     *     pattern="/[0-9]{15}/",
     *     message="Le numéro identifiant d'une puce électronique contient 15 chiffres"
	 * )
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $veterinaryName;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="ownerAddressCat", orphanRemoval=true)
     */
    private $ownerAddress;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="veterinaryAddressCat", orphanRemoval=true)
     */
    private $veterinaryAddress;

    /**
     * @ORM\OneToMany(targetEntity=Measure::class, mappedBy="cat", orphanRemoval=true)
     */
    private $measures;

    /**
     * @ORM\OneToMany(targetEntity=PetCare::class, mappedBy="cat", orphanRemoval=true)
     */
    private $petCares;

    public function __construct()
    {
        $this->ownerAddress = new ArrayCollection();
        $this->veterinaryAddress = new ArrayCollection();
        $this->measures = new ArrayCollection();
        $this->petCares = new ArrayCollection();
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

    public function getDateOfDeath(): ?\DateTimeInterface
    {
        return $this->dateOfDeath;
    }

    public function setDateOfDeath(?\DateTimeInterface $dateOfDeath): self
    {
        $this->dateOfDeath = $dateOfDeath;

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
    public function getOwnerAddress(): Collection
    {
        return $this->ownerAddress;
    }

    public function addOwnerAddress(Address $ownerAddress): self
    {
        if (!$this->ownerAddress->contains($ownerAddress)) {
            $this->ownerAddress[] = $ownerAddress;
            $ownerAddress->setOwnerAddressCat($this);
        }

        return $this;
    }

    public function removeOwnerAddress(Address $ownerAddress): self
    {
        if ($this->ownerAddress->removeElement($ownerAddress)) {
            // set the owning side to null (unless already changed)
            if ($ownerAddress->getOwnerAddressCat() === $this) {
                $ownerAddress->setOwnerAddressCat(null);
            }
        }

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
            $veterinaryAddress->setVeterinaryAddressCat($this);
        }

        return $this;
    }

    public function removeVeterinaryAddress(Address $veterinaryAddress): self
    {
        if ($this->veterinaryAddress->removeElement($veterinaryAddress)) {
            // set the owning side to null (unless already changed)
            if ($veterinaryAddress->getVeterinaryAddressCat() === $this) {
                $veterinaryAddress->setVeterinaryAddressCat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Measure[]
     */
    public function getMeasures(): Collection
    {
        return $this->measures;
    }

    public function addMeasure(Measure $measure): self
    {
        if (!$this->measures->contains($measure)) {
            $this->measures[] = $measure;
            $measure->setCat($this);
        }

        return $this;
    }

    public function removeMeasure(Measure $measure): self
    {
        if ($this->measures->removeElement($measure)) {
            // set the owning side to null (unless already changed)
            if ($measure->getCat() === $this) {
                $measure->setCat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PetCare[]
     */
    public function getPetCares(): Collection
    {
        return $this->petCares;
    }

    public function addPetCare(PetCare $petCare): self
    {
        if (!$this->petCares->contains($petCare)) {
            $this->petCares[] = $petCare;
            $petCare->setCat($this);
        }

        return $this;
    }

    public function removePetCare(PetCare $petCare): self
    {
        if ($this->petCares->removeElement($petCare)) {
            // set the owning side to null (unless already changed)
            if ($petCare->getCat() === $this) {
                $petCare->setCat(null);
            }
        }

        return $this;
    }
}

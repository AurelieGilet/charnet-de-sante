<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Regex(
     *     pattern="/[0-9]{5}/",
     *     message="Le code postal doit contenir 5 chiffres"
	 * )
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Regex(
     *     pattern="/[0-9]{10}/",
     *     message="Le numéro de téléphone doit contenir 10 chiffres"
	 * )
     */
    private $phoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Cat::class, inversedBy="ownerAddress")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ownerAddressCat;

    /**
     * @ORM\ManyToOne(targetEntity=Cat::class, inversedBy="veterinaryAddress")
     * @ORM\JoinColumn(nullable=true)
     */
    private $veterinaryAddressCat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(?string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getOwnerAddressCat(): ?Cat
    {
        return $this->ownerAddressCat;
    }

    public function setOwnerAddressCat(?Cat $ownerAddressCat): self
    {
        $this->ownerAddressCat = $ownerAddressCat;

        return $this;
    }

    public function getVeterinaryAddressCat(): ?Cat
    {
        return $this->veterinaryAddressCat;
    }

    public function setVeterinaryAddressCat(?Cat $veterinaryAddressCat): self
    {
        $this->veterinaryAddressCat = $veterinaryAddressCat;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}

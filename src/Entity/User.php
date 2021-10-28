<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *      fields="email",
 *      message="Un compte utilise déjà cet email",
 * )
 * @UniqueEntity(
 *      fields="username",
 *      message="Ce nom d'utilisateur est déjà pris",
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Assert\NotBlank(
     *      message="Merci de saisir une adresse email"
     * )
     * 
     * @Assert\Email(
     *      message="Merci de saisir un email valide"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
	 * 
	 * @Assert\NotBlank(
	 * 		message="Merci de saisir un mot de passe"
	 * )
     * 
     * @Assert\Regex(
     *     pattern="/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}/",
     *     message="Votre mot de passe doit faire entre 8 et 30 caractères et contenir une majuscule, une minuscule et un chiffre"
	 * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *      message="Merci de saisir un nom d'utilisateur"
     * )
     * 
     * @Assert\Length(
	 * 		min="3", 
	 * 		minMessage="Votre nom d'utilisateur doit faire au moins 3 caractères",
     *      max="30",
     *      maxMessage="Votre nom d'utilisateur doit faire moins de 30 caractères"
	 * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Cat::class, mappedBy="owner", orphanRemoval=true)
     */
    private $cats;

    /**
     * @ORM\OneToOne(targetEntity=Guest::class, mappedBy="User", cascade={"persist", "remove"})
     */
    private $guest;

    /**
     * @ORM\OneToOne(targetEntity=GuestCode::class, mappedBy="User", cascade={"persist", "remove"})
     */
    private $guestCode;

    public function __construct()
    {
        $this->cats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    /**
     * @return Collection|Cat[]
     */
    public function getCats(): Collection
    {
        return $this->cats;
    }

    public function addCat(Cat $cat): self
    {
        if (!$this->cats->contains($cat)) {
            $this->cats[] = $cat;
            $cat->setOwner($this);
        }

        return $this;
    }

    public function removeCat(Cat $cat): self
    {
        if ($this->cats->removeElement($cat)) {
            // set the owning side to null (unless already changed)
            if ($cat->getOwner() === $this) {
                $cat->setOwner(null);
            }
        }

        return $this;
    }

    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    public function setGuest(Guest $guest): self
    {
        // set the owning side of the relation if necessary
        if ($guest->getUser() !== $this) {
            $guest->setUser($this);
        }

        $this->guest = $guest;

        return $this;
    }

    public function getGuestCode(): ?GuestCode
    {
        return $this->guestCode;
    }

    public function setGuestCode(GuestCode $guestCode): self
    {
        // set the owning side of the relation if necessary
        if ($guestCode->getUser() !== $this) {
            $guestCode->setUser($this);
        }

        $this->guestCode = $guestCode;

        return $this;
    }
}

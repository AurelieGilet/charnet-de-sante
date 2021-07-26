<?php

namespace App\Entity;

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
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * 
     * @Assert\NotBlank(
     *      message="Merci de saisir une adresse email"
     * )
     * 
     * @Assert\Email(
     *      message="Merci de saisir un email valide"
     * )
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
     *     pattern="/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/",
     *     message="Votre mot de passe doit faire au moins 8 caractères et contenir une majuscule, une minuscule et un chiffre"
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
        return (string) $this->email;
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
}

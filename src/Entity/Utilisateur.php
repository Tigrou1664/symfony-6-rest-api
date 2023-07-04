<?php

namespace App\Entity;

use App\Entity\Boutique;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length(max: 180, maxMessage: 'length.error')]
    #[Assert\Type(type: 'string', message: 'type.error')]
    private string $login;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    /**
     * The plainPassword is used to provide a password that will be encoded before
     * persisting in the database in the `password` property
     * (e.g. password in a login form or user creation by an API endpoint).
     */
    #[Assert\Type(type: 'string', message: 'type.error')]
    private ?string $plainPassword = '';

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'required.displayName')]
    #[Assert\Length(max: 255, maxMessage: 'length.error')]
    #[Assert\Type(type: 'string', message: 'type.error.string')]
    private string $displayName;

    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: BoutiqueUtilisateur::class)]
    private $boutiques;

    public function __construct()
    {
        $this->boutiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return ArrayCollection|Boutique[]
     */
    public function getBoutiques(): ArrayCollection
    {
        return $this->boutiques;
    }
    public function addBoutique(Boutique $boutique)
    {
        if (!$this->boutiques->contains($boutique)) {
            $this->boutiques[] = $boutique;
            $boutique->addPersonnel($this);
        }
    }
    public function removeBoutique(Boutique $boutique)
    {
        if ($this->boutiques->contains($boutique)) {
            $this->boutiques->removeElement($boutique);
            $boutique->removePersonnel($this);
        }
    }
}

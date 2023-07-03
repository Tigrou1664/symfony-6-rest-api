<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoutiqueRepository::class)]
class Boutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'required.name')]
    #[Assert\Length(max: 255, maxMessage: 'length.error')]
    #[Assert\Type(type: 'string', message: 'type.error.string')]
    private string $nom = '';

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: BoutiqueUtilisateur::class)]
    private $personnel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUtilisateurs(): Collection
    {
        return $this->personnel;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->personnel->contains($utilisateur)) {
            $this->personnel[] = $utilisateur;
            $utilisateur->setOwner($this);
        }

        return $this;
    }
}

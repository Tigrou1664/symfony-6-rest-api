<?php

namespace App\Entity;

use App\DBAL\Types\RoleType;
use App\Entity\Boutique;
use App\Entity\Utilisateur;
use App\Repository\BoutiqueUtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoutiqueUtilisateurRepository::class)]
class BoutiqueUtilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Boutique::class, inversedBy: 'personnel')]
    #[ORM\JoinColumn(nullable: false)]
    private Boutique $boutique;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'boutiques')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $utilisateur;

    // Role type. List of the allowed Role types:
    // - 'vendor' for a vendor user in shop
    // - 'admin' for an admin user in shop
    #[Assert\Choice(callback: 'getRoleTypes', message: 'role_type.not_valid')]
    #[ORM\Column(name: 'role', type: 'enum_role_type', nullable: false)]
    private string $role = RoleType::ROLE_DEFAULT;
    
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

    public function getRole(): string
    {
        return $this->role;
    }
    
    public function setRole(string $role): self
    {
        $this->role = $role;
        
        return $this;
    }

    public function getBoutique(): Boutique
    {
        return $this->boutique;
    }
    public function setBoutique(Boutique $boutique)
    {
        $this->boutique = $boutique;
    }
    
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }
    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }
}

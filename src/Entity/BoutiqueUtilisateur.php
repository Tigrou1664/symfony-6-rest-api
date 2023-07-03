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

    // Role type. List of the allowed Role types:
    // - 'vendor' for a vendor user in shop
    // - 'admin' for an admin user in shop
    #[Assert\Choice(callback: 'getRoleTypes', message: 'role_type.not_valid')]
    #[ORM\Column(name: 'role', type: 'enum_role_type', nullable: false)]
    private string $role = RoleType::ROLE_DEFAULT;

    #[ORM\ManyToOne(targetEntity: 'Boutique')]
    #[ORM\JoinColumn(nullable: false)]
    private Boutique $boutique;

    #[ORM\ManyToOne(targetEntity: 'Utilisateur')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $utilisateur;
    

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
    public function getRoleTypes(): array
    {
        return (new RoleType())->getValues();
    }

    public function getBoutique(): Boutique
    {
        return $this->boutique;
    }
    public function setBoutique($boutique): Boutique
    {
        $this->boutique = $boutique;
    }
    public function getUtilisateur(): Uilisateur
    {
        return $this->utilisateur;
    }
    public function setUtilisateur($utilisateur): Utilisateur
    {
        $this->utilisateur = $utilisateur;
    }
}

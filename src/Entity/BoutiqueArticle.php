<?php

namespace App\Entity;

use App\Entity\Boutique;
use App\Entity\Article;
use App\Repository\BoutiqueUtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoutiqueUtilisateurRepository::class)]
class BoutiqueArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $stock = 0;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $tarifLocationJour = 0;

    #[ORM\ManyToOne(targetEntity: 'Boutique')]
    #[ORM\JoinColumn(nullable: false)]
    private Boutique $boutique;

    #[ORM\ManyToOne(targetEntity: 'Article')]
    #[ORM\JoinColumn(nullable: false)]
    private Article $article;
    

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
    
    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
    
    public function getTarifLocationJour(): ?int
    {
        return $this->tarifLocationJour;
    }

    public function setTarifLocationJour(?int $tarifLocationJour): self
    {
        $this->tarifLocationJour = $tarifLocationJour;

        return $this;
    }
    
    public function getBoutique(): Boutique
    {
        return $this->boutique;
    }
    
    public function setBoutique($boutique): Boutique
    {
        $this->boutique = $boutique;
        
        return $this;
    }
    
    public function getUtilisateur(): Uilisateur
    {
        return $this->utilisateur;
    }
    public function setUtilisateur($utilisateur): Utilisateur
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}

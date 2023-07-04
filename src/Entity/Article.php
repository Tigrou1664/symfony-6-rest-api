<?php

namespace App\Entity;

use App\Entity\Boutique;
use App\Entity\BoutiqueArticle;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nom = '';

    #[ORM\Column]
    private float $prix = 0;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: BoutiqueArticle::class)]
    private $boutiques;

    public function __construct()
    {
        $this->boutiques = new ArrayCollection();
    }

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

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
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
            $boutique->addArticle($this);
        }
    }
    public function removeBoutique(Boutique $boutique)
    {
        if ($this->boutiques->contains($boutique)) {
            $this->boutiques->removeElement($boutique);
            $boutique->removeArticle($this);
        }
    }
}

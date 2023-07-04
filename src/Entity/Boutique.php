<?php

namespace App\Entity;

use App\DBAL\Types\RoleType;
use App\Entity\Article;
use App\Entity\BoutiqueArticle;
use App\Entity\BoutiqueUtilisateur;
use App\Entity\Utilisateur;
use App\Repository\BoutiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: BoutiqueArticle::class)]
    private $articles;

    public function __construct()
    {
        $this->personnel = new ArrayCollection();
        $this->articles = new ArrayCollection();
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

    /**
     * @return ArrayCollection|Utilisateur[]
     */
    public function getPersonnel(): Collection
    {
        return $this->personnel;
    }

    public function addPersonnel(Utilisateur $utilisateur)
    {
        if (!$this->personnel->contains($utilisateur)) {
            $this->personnel[] = $utilisateur;
            $utilisateur->addBoutique($this);
        }
    }

    public function addVendor(Utilisateur $utilisateur)
    {
        if (!$this->personnel->contains($utilisateur)) {
            $this->personnel[] = $utilisateur;
            $utilisateur->addBoutique($this);
//            $relation = new BoutiqueUtilisateur();
//            $relation->setBoutique($this);
//            $relation->setUtilisateur($utilisateur);
//            $relation->setRole(RoleType::ROLE_VENDOR);
        }
    }

    public function addAdmin(Utilisateur $utilisateur)
    {
        if (!$this->personnel->contains($utilisateur)) {
            $this->personnel[] = $utilisateur;
            $utilisateur->addBoutique($this);
//            $relation = new BoutiqueUtilisateur();
//            $relation->setBoutique($this);
//            $relation->setUtilisateur($utilisateur);
//            $relation->setRole(RoleType::ROLE_ADMIN);
//            $relation->
        }
    }

    public function removePersonnel(Utilisateur $user)
    {
        if ($this->personnel->contains($user)) {
            $this->personnel->removeElement($user);
            // not needed for persistence, just keeping both sides in sync
            $user->removeBoutique($this);
        }
    }

    /**
     * @return ArrayCollection|Article[]
     */
    public function getArticles(): ArrayCollection
    {
        return $this->articles;
    }
    public function addArticle(Article $article)
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addBoutique($this);
        }
    }
    public function removeBoutique(Article $article)
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeBoutique($this);
        }
    }
}

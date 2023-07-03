<?php 

namespace App\DataFixtures;

use App\DBAL\Types\RoleType;
use App\Entity\Article;
use App\Entity\Boutique;
use App\Entity\BoutiqueArticle;
use App\Entity\BoutiqueUtilisateur;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create admins & vendors
        $user = new Utilisateur();
        $user->setLogin('admin1');
        $user->setPlainPassword('admin1');
        $user->setDisplayName('Admin 1');
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);
        $this->addReference('admin1', $user);

        $user = new Utilisateur();
        $user->setLogin('admin2');
        $user->setPlainPassword('admin2');
        $user->setDisplayName('Admin 2');
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);

        $user = new Utilisateur();
        $user->setLogin('vendor1');
        $user->setPlainPassword('vendor1');
        $user->setDisplayName('Vendeur 1');
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);
        $this->addReference('vendor1', $user);

        $user = new Utilisateur();
        $user->setLogin('vendor2');
        $user->setPlainPassword('vendor2');
        $user->setDisplayName('Vendeur 2');
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);
        
        // Create shops
        $shop = new Boutique();
        $shop->setNom('Paris');
        $manager->persist($shop);
        $this->addReference('shop1', $shop);

        $shop = new Boutique();
        $shop->setNom('Nimes');
        $manager->persist($shop);

        // Set admin1 et vendor1 to boutique1
//        $relation = new BoutiqueUtilisateur();
//        $relation->setBoutique($this->getReference('shop1'));
//        $relation->setUtilisateur($this->getReference('admin1'));
//        $relation->setRole(RoleType::ROLE_ADMIN);
//        $manager->persist($relation);
//
//        $relation = new BoutiqueUtilisateur();
//        $relation->setBoutique($this->getReference('shop1'));
//        $relation->setUtilisateur($this->getReference('vendor1'));
//        $relation->setRole(RoleType::ROLE_VENDOR);
//        $manager->persist($relation);
        
        // Create articles
        $article = new Article();
        $article->setNom('Ski');
        $article->setPrix(500);
        $manager->persist($article);
        $this->addReference('article1', $article);

        $article = new Article();
        $article->setNom('Batons');
        $article->setPrix(100);
        $manager->persist($article);

        $article = new Article();
        $article->setNom('Casque');
        $article->setPrix(100);
        $manager->persist($article);
        $this->addReference('article3', $article);

        // Set article1 & article3 to boutique1
//        $relation = new BoutiqueArticle();
//        $relation->setBoutique($this->getReference('shop1'));
//        $relation->setArticle($this->getReference('article1'));
//        $relation->setStock(10);
//        $relation->setTarifLocationJour(25);
//        $manager->persist($relation);
//
//        $relation = new BoutiqueArticle();
//        $relation->setBoutique($this->getReference('shop1'));
//        $relation->setArticle($this->getReference('article3'));
//        $relation->setStock(20);
//        $relation->setTarifLocationJour(5);
//        $manager->persist($relation);
        
        $manager->flush();
    }
}
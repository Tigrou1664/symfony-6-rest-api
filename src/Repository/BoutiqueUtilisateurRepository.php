<?php

namespace App\Repository;

use App\Entity\BoutiqueUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BoutiqueArticle>
 *
 * @method BoutiqueUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoutiqueUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoutiqueUtilisateur[]    findAll()
 * @method BoutiqueUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiqueUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoutiqueUtilisateur::class);
    }

    public function save(BoutiqueUtilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BoutiqueUtilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

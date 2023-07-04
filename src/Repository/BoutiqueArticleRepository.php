<?php

namespace App\Repository;

use App\Entity\BoutiqueArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BoutiqueArticle>
 *
 * @method BoutiqueArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoutiqueArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoutiqueArticle[]    findAll()
 * @method BoutiqueArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiqueArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoutiqueArticle::class);
    }

    public function save(BoutiqueArticle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BoutiqueArticle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Article Returns an array of Article objects
     */
    public function findArticleByShop($id, $shopId): Article|null
    {
        return $this->createQueryBuilder('a')
            ->select('a.id', 'a.nom', 'a.prix', 'ba.stock', 'ba.tarifLocationJour')
            ->innerJoin('a.boutiques', 'ba')
            ->andWhere('ba.boutique = :shopId')
            ->setParameter('shopId', $shopId)
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findArticlesByShop($value): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.id', 'a.nom', 'a.prix', 'ba.stock', 'ba.tarifLocationJour')
            ->innerJoin('a.boutiques', 'ba')
            ->andWhere('ba.boutique = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}

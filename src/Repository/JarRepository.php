<?php

namespace App\Repository;

use App\Entity\Jar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jar>
 */
class JarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jar::class);
    }

    //    /**
    //     * @return Jar[] Returns an array of Jar objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Jar
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countActiveJars(): int
    {
        return $this->createQueryBuilder('j')
            ->select('COUNT(j.id)')
            ->where('j.status = :status')
            ->setParameter('status', 'aktywny')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMostPopularContent(): ?array
    {
        return $this->createQueryBuilder('j')
            ->select('j.content as name, COUNT(j.id) as amount')
            ->where('j.status = :status')
            ->setParameter('status', 'aktywny')
            ->groupBy('j.content')
            ->orderBy('amount', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->findBy(['status' => 'aktywny'], ['createdAt' => 'DESC'], $limit);
    }
}

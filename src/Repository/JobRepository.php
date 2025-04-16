<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function findBySearch(?string $search): array
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('j.companies', 'c')
            ->leftJoin('j.categories', 'cat')
            ->andWhere('j.title LIKE :search OR j.description LIKE :search OR c.name LIKE :search OR cat.type LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('j.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findPaginated(int $page, int $limit = 10): array
    {
        return $this->createQueryBuilder('j')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countAllJobs(): int
    {
        return (int) $this->createQueryBuilder('j')
            ->select('COUNT(j.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    //    /**
    //     * @return Job[] Returns an array of Job objects
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

    //    public function findOneBySomeField($value): ?Job
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

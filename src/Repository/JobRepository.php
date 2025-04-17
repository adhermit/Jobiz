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

    public function findBySearch(?string $search, ?int $minSalary, ?int $maxSalary, ?string $country, ?string $city, ?int $categoryId): array
    {
        $qb = $this->createQueryBuilder('j');
    
        if ($search) {
            $qb->andWhere('j.title LIKE :search OR j.description LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
    
        if ($minSalary) {
            $qb->andWhere('j.minSalary >= :minSalary')
               ->setParameter('minSalary', $minSalary);
        }
    
        if ($maxSalary) {
            $qb->andWhere('j.maxSalary <= :maxSalary')
               ->setParameter('maxSalary', $maxSalary);
        }
    
        if ($country) {
            $qb->andWhere('j.country = :country')
               ->setParameter('country', $country);
        }
    
        if ($city) {
            $qb->andWhere('j.city = :city')
               ->setParameter('city', $city);
        }
    
        if ($categoryId) {
            $qb->join('j.categories', 'c')
               ->andWhere('c.id = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }
    
        return $qb->getQuery()->getResult();
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

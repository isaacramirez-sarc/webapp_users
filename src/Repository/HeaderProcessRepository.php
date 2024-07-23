<?php

namespace App\Repository;

use App\Entity\HeaderProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<HeaderProcess>
 *
 * @method HeaderProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeaderProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeaderProcess[]    findAll()
 * @method HeaderProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeaderProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeaderProcess::class);
    }


    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('h')
            ->where('h.executionDate BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }
}

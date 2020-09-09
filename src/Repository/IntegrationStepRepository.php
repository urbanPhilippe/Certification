<?php

namespace App\Repository;

use App\Entity\IntegrationStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IntegrationStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntegrationStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntegrationStep[]    findAll()
 * @method IntegrationStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntegrationStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntegrationStep::class);
    }

    // /**
    //  * @return IntegrationStep[] Returns an array of IntegrationStep objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IntegrationStep
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

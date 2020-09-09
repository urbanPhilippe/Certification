<?php

namespace App\Repository;

use App\Entity\Content;
use App\Entity\ContentSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Content::class);
    }

    public function searchContent(ContentSearch $search)
    {
        $query = $this->findAllQuery();

        if ($search->getText()) {
            $words = explode(' ', $search->getText());

            $clauses = '';
            $parameters = [];
            $i = 0;
            foreach ($words as $word) {
                $parameters[':val' . $i] = '%' . $word . '%';
                if ($i === 0) {
                    $clauses = 'c.title LIKE :val' . $i . ' OR c.content LIKE :val' . $i;
                } else {
                    $clauses .= ' AND c.title LIKE :val' . $i . ' OR c.content LIKE :val' . $i;
                }
                $i++;
            }

            $query = $query
                ->andwhere($clauses)
                ->setParameters($parameters);
        }

        if ($search->getCategory()) {
            $query = $query
                ->join('c.category', 'ca')
                ->andwhere('ca.name = :category')
                ->setParameter('category', $search->getCategory()->getName());
        }

        return $query->getQuery()->getResult();
    }

    public function findAllQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('c');
    }

    // /**
    //  * @return Content[] Returns an array of Content objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Content
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

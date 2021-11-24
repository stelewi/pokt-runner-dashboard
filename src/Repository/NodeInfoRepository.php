<?php

namespace App\Repository;

use App\Entity\NodeInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NodeInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method NodeInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method NodeInfo[]    findAll()
 * @method NodeInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NodeInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeInfo::class);
    }

    // /**
    //  * @return NodeInfo[] Returns an array of NodeInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NodeInfo
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\MemberMembership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MemberMembership|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberMembership|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberMembership[]    findAll()
 * @method MemberMembership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberMembershipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberMembership::class);
    }

    // /**
    //  * @return MemberMembership[] Returns an array of MemberMembership objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MemberMembership
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

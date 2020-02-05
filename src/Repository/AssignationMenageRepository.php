<?php

namespace App\Repository;

use App\Entity\AssignationMenage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssignationMenage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssignationMenage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssignationMenage[]    findAll()
 * @method AssignationMenage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignationMenageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssignationMenage::class);
    }

    // /**
    //  * @return AssignationMenage[] Returns an array of AssignationMenage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssignationMenage
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

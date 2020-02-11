<?php

namespace App\Repository;

use App\Entity\AssignationChambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssignationChambre|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssignationChambre|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssignationChambre[]    findAll()
 * @method AssignationChambre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignationChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssignationChambre::class);
    }

    # Requete findAll + groupeBy
    public function findAllGroupeBy($value)
    {
        return $this->createQueryBuilder('r')
            ->groupBy('r.'.$value)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return AssignationChambre[] Returns an array of AssignationChambre objects
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
    public function findOneBySomeField($value): ?AssignationChambre
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

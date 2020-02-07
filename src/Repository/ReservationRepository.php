<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }



    public function findReservation($valeurExclue)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.status <> :val')
            ->setParameter('val', $valeurExclue)
            ->getQuery()
            ->getResult()
        ;
    }

    #Requete pour trouver les résa dont la date d'entrée est la date du jour
    public function findCheckin()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('CURRENT_DATE() = r.dateEntree')
            ->getQuery()
            ->getResult()
        ;
    }


        #Requete pour trouver les résa dont la date de sortie est la date du jour
        public function findCheckOut()
        {
            return $this->createQueryBuilder('r')
                ->andWhere('CURRENT_DATE() = r.dateSortie')
                ->getQuery()
                ->getResult()
            ;
        }






    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */








    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

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


    # Requete findAll + groupeBy
    public function findAllGroupeBy($value)
    {
        return $this->createQueryBuilder('r')
            ->groupBy('r.'.$value)
            ->getQuery()
            ->getResult()
        ;
    }


    # Requete pour filtrer l'historique des réservations après validation d'envoi du formulaire
    public function historiqueAssignationFiltre($date, $employe, $chambre, $option)
    {
        $query = $this->createQueryBuilder('a')
            ->innerJoin('a.chambre', 'c') 
            ->innerJoin('a.employe', 'e') 
            ->innerJoin('a.optionService', 'o');

            # je teste si la date est renseignée
            if(!empty( $date)) {
                $query = $query
                ->andWhere('a.date = :val')
                ->setParameter('val', $date);
            }

            # je teste si l'employé est renseigné
            if(!empty( $employe)) {
                $query = $query
                ->andWhere('e.username = :val2')
                ->setParameter('val2', $employe);
            }

            # je teste si la chambre est renseignée
            if(!empty( $chambre)) {
                $query = $query
                ->andWhere('c.nom = :val3')
                ->setParameter('val3', $chambre);
            }

            # je teste si l'option est renseignée
            if(!empty( $option)) {
                $query = $query
                ->andWhere('o.nomOption = :val3')
                ->setParameter('val3', $option);
            }

            # je teste tri par date, du plus récent au plus ancien
            $query = $query
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();

            return $query;
    }


    

    # Requête pour trouver la dernière assignation
    public function findLastAssignation($chambre) {
        return $this->createQueryBuilder('a')
        ->andWhere('a.chambre = :chambre' )
        ->setMaxResults(1)
        ->setParameter('chambre', $chambre)
        ->orderBy('a.id', 'DESC')
        ->getQuery()
        ->getOneOrNullResult()
        ;
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

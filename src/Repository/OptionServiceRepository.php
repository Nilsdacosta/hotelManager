<?php

namespace App\Repository;

use App\Entity\OptionService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OptionService|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionService|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionService[]    findAll()
 * @method OptionService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionService::class);
    }


    # Requete findAll + groupeBy
    public function findAllGroupeBy($value)
    {
        return $this->createQueryBuilder('o')
            ->groupBy('o.'.$value)
            ->getQuery()
            ->getResult()
        ;
    }

    # Requete pour filtrer l'historique des réservations
    public function optionFiltre($id,$nom,$dateCreation,$prix)
    {
        $query = $this->createQueryBuilder('o');

       # je vérifie si l'id est renseigné        
        if(!empty( $id)) {
            $query = $query
            ->andWhere('o.id = :val')
            ->setParameter('val', $id);
        }

       # je vérifie le nom est renseigné
        if(!empty( $nom)) {
            $query = $query
            ->andWhere('o.nomOption = :val2')
            ->setParameter('val2', $nom);
        }

       # je vérifie si la date de création est renseignée
        if(!empty( $dateCreation)) {
            $query = $query
            ->andWhere('o.dateCreation = :val3')
            ->setParameter('val3', $dateCreation);
        }

       # je vérifie si le prix est renseigné
        if(!empty( $prix)) {
            $query = $query
            ->andWhere('o.prixOption LIKE :val')
            ->setParameter('val', $prix);
        }

        $query = $query
        ->getQuery()
        ->getResult();

        return $query;
    }

    // /**
    //  * @return OptionService[] Returns an array of OptionService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionService
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

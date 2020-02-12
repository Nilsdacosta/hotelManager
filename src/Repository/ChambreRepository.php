<?php

namespace App\Repository;

use App\Entity\Chambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Chambre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chambre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chambre[]    findAll()
 * @method Chambre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chambre::class);
    }


     # Requete findAll + groupeBy
     public function findAllGroupeBy($value)
     {
         return $this->createQueryBuilder('c')
             ->groupBy('c.'.$value)
             ->getQuery()
             ->getResult()
         ;
     }


    # Requete pour afficher l'historique des réservations après les filtres
    public function chambreFiltre($id,$capacite, $etat,$description, $prix,$nom)
    {
        $query = $this->createQueryBuilder('c');
 
        # je vérifie si l'id est renseigné
        if(!empty( $id)) {
            $query = $query
            ->andWhere('c.id = :val')
            ->setParameter('val', $id);
        }

        # je vérifie si la capacité est renseignée
        if(!empty( $capacite)) {
            $query = $query
            ->andWhere('c.capacite = :val2')
            ->setParameter('val2', $capacite);
        }

        # je vérifie si l'état est renseigné
        if(!empty( $etat)) {
            $query = $query
            ->andWhere('c.etat = :val3')
            ->setParameter('val3', $etat);
        }

        # je vérifie si la description est renseigné
        if(!empty( $description)) {
            $query = $query
            ->andWhere('c.description LIKE :val4')
            ->setParameter('val4', '%'.$description.'%');
        }

        # je vérifie si le prix est renseigné
        if(!empty( $prix)) {
            $query = $query
            ->andWhere('c.prix = :val6')
            ->setParameter('val6', $prix);
        }

        # je vérifie si le nom est renseigné
        if(!empty( $nom)) {
            $query = $query
            ->andWhere('c.nom = :val7')
            ->setParameter('val7', $nom);
        }
        $query = $query
        ->getQuery()
        ->getResult();

        return $query;
    }


     

    // /**
    //  * @return Chambre[] Returns an array of Chambre objects
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
    public function findOneBySomeField($value): ?Chambre
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

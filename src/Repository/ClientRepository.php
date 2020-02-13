<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }


    # Requête findAll + groupeBy
    public function findAllGroupeBy($value)
    {
        return $this->createQueryBuilder('c')
            ->groupBy('c.'.$value)
            ->getQuery()
            ->getResult()
        ;
    }

      

    # Requete pour filtrer les clients
    public function historiqueClientFiltre($nomClient, $prenomClient, $adresse,$codePostal, $ville,$telephone,$mail,$dateNaissance)
    {
        $query = $this->createQueryBuilder('c');

       # je vérifie si le nom est renseigné
        if(!empty( $nomClient)) {
            $query = $query
            ->andWhere('c.nom LIKE :val')
            ->setParameter('val', '%'.$nomClient.'%');
        }

        # je vérifie si le prénom est renseigné
        if(!empty( $prenomClient)) {
            $query = $query
            ->andWhere('c.prenom LIKE :val2')
            ->setParameter('val2', '%'.$prenomClient.'%');
        }
        
        # je vérifie si l'adresse est renseignée
        if(!empty( $adresse)) {
            $query = $query
            ->andWhere('c.adresse LIKE :val3')
            ->setParameter('val3', '%'.$adresse.'%');
        }

        # je vérifie si le CP est renseigné
        if(!empty( $codePostal)) {
            $query = $query
            ->andWhere('c.codePostal LIKE :val4')
            ->setParameter('val4', '%'.$codePostal.'%');
        }

       # je vérifie si la ville est renseignée
        if(!empty( $ville)) {
            $query = $query
            ->andWhere('c.ville LIKE :val5')
            ->setParameter('val5', '%'.$ville.'%');
        }

        # je vérifie si le téléphone est renseigné
        if(!empty( $telephone)) {
            $query = $query
            ->andWhere('c.telephone LIKE :val6')
            ->setParameter('val6', '%'.$telephone.'%');
        }

        # je vérifie si l'email est renseigné
        if(!empty( $mail)) {
            $query = $query
            ->andWhere('c.mail LIKE :val7')
            ->setParameter('val7', '%'.$mail.'%');
        }

        # je vérifie si la date de naissance est renseignée
        if(!empty( $dateNaissance)) {
            $query = $query
            ->andWhere('c.dateDeNaissance = :val8')
            ->setParameter('val8', $dateNaissance);
        }

        # je tri par nom, ordre croissant
        $query = $query
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();

        return $query;
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
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
    public function findOneBySomeField($value): ?Client
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

<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Chambre;
use App\Entity\Reservation;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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


    # Requete pour récupérer les réservation qui ne sont pas annulées
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
 
        //public function findCheckOut()
        //{
        //    return $this->createQueryBuilder('r')
        //        ->andWhere('CURRENT_DATE() = r.dateSortie')
        //        ->getQuery()
        //        ->getResult()
        //    ;
        //}

    # Requête pour compter le nombre de checkin du jour (checkin = dateEntrée = date du jour)
    public function countin()
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT (r.id)')
            ->andWhere('CURRENT_DATE() = r.dateEntree')
            ->getQuery()
            ->getOneOrNullresult()
        ;
    }

    # Requête pour compter le nombre de checkout du jour (checkout = dateSortie = date du jour)
    public function countout()
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT (r.id)')
            ->andWhere('CURRENT_DATE() = r.dateSortie')
            ->getQuery()
            ->getOneOrNullresult()
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

    # Requête pour créer le filtre capacite
    public function filtreHistoriqueResa($capacite)
    {
        return $this->createQueryBuilder('r')
            ->select('r','c')
            ->join('r.chambre', 'c')
            ->where('c.capacite LIKE :capacite')
            ->setParameter('capacite', $capacite)
            ->getQuery()
            ->getResult()
            ;
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


    /**
     * @return Reservation[] id, capacite,dateCreation, nomChambre, nomClient, prenomClient,dateEntree, dateSortie, OptionService
     */
    # Requete pour afficher l'historique des réservations après les filtres
    public function historiqueResaFiltre($id,$capacite,$dateCreation,$nomChambre,$nomClient,$prenomClient, $etatChambre,$dateEntree, $dateSortie, $optionService)
    {
        $query = $this->createQueryBuilder('r')
            ->innerJoin('r.chambre', 'c') 
            ->innerJoin('r.client', 'cl') 
            ->innerJoin('r.optionService', 'o');

            # Je vérifie si l'id est renseigné
            if(!empty( $id)) {
                $query = $query
                ->andWhere('r.id = :val')
                ->setParameter('val', $id);
            }

             # Je vérifie si la capacité est renseignée
            if(!empty($capacite)) {
                    $query = $query
                ->andWhere('c.capacite = :val2')
                ->setParameter('val2', $capacite);
            }

            # Je vérifie si la date de création est renseignée
            if(!empty($dateCreation)) {
                $query = $query
                ->andWhere('r.dateCreation = :val3')
                ->setParameter('val3', $dateCreation);
            }

            # Je vérifie si le nom de la chambre est renseigné
            if(!empty($nomChambre)) {
                $query = $query
                ->andWhere('c.nom = :val4')
                ->setParameter('val4', $nomChambre);
            }

            # Je vérifie si le nom client est renseigné
            if(!empty($nomClient)) {
                $query = $query
                ->andWhere('cl.nom LIKE :val5')
                ->setParameter('val5', '%'.$nomClient.'%');
            }

            # Je vérifie si le prénom est renseigné
            if(!empty($prenomClient)) {
                $query = $query
                ->andWhere('cl.prenom = :val6')
                ->setParameter('val6', '%'.$prenomClient.'%');
            }

            # Je vérifie si l'état est renseigné
            if(!empty($etatChambre)) {
                $query = $query
                ->andWhere('c.etat= :val7')
                ->setParameter('val7', $etatChambre);
            }

            # Je vérifie si la date d'entrée est renseignée
            if(!empty($dateEntree)) {
                $query = $query
                ->andWhere('r.dateEntree= :val8')
                ->setParameter('val8', $dateEntree);
            }

            # Je vérifie si la date de sortie est renseignée
            if(!empty($dateSortie)) {
                $query = $query
                ->andWhere('r.dateSortie= :val9')
                ->setParameter('val9', $dateSortie);
            }

            # Je vérifie si l'option est renseignée
            if(!empty($optionService)) {
                $query = $query
                ->andWhere('o.nomOption= :val10')
                ->setParameter('val10', $optionService);
            };
            
            # Je tri par date de création, ordre décroissant
            $query = $query
            ->orderBy('r.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();

            return $query;
    }       

    # Requete findAll + groupeBy
    public function findResaDashboard($date)
    {
        return $this->createQueryBuilder('r')
            ->andwhere(':date BETWEEN r.dateEntree AND r.dateSortie')
            ->setParameter('date', $date)
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

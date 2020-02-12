<?php

namespace App\Repository;

use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Employe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employe[]    findAll()
 * @method Employe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employe::class);
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

    # Requete findRole + groupeBy
    public function findRoleGroupeBy($value)
    {
        return $this->createQueryBuilder('r')
            ->select('r.'.$value)
            ->groupBy('r.'.$value)
            ->getQuery()
            ->getResult()
        ;
    }

    # Requete pour afficher l'historique des réservations après les filtres
    public function employeFiltre($id, $username, $nom, $prenom, $telephone, $poste, $role)
    {
        $query = $this->createQueryBuilder('e');

        # je vérifie si l'id est renseigné
        if(!empty( $id)) {
            $query = $query
            ->andWhere('e.id = :val')
            ->setParameter('val', $id);
        }

        # je vérifie si le username est renseigné
        if(!empty( $username)) {
            $query = $query
            ->andWhere('e.username LIKE :val2')
            ->setParameter('val2', '%'.$username.'%');
        }

       # je vérifie si le nom  est renseigné
        if(!empty( $nom)) {
            $query = $query
            ->andWhere('e.nom LIKE :val3')
            ->setParameter('val3', '%'.$nom.'%');
        }

       # je vérifie si le prénom  est renseigné
        if(!empty( $prenom)) {
            $query = $query
            ->andWhere('e.prenom LIKE :val4')
            ->setParameter('val4', '%'.$prenom.'%');
        }

       # je vérifie si le téléphone est renseigné
        if(!empty( $telephone)) {
            $query = $query
            ->andWhere('e.telephone LIKE :val5')
            ->setParameter('val5', '%'.$telephone.'%');
        }

       # je vérifie si le poste est renseigné
        if(!empty( $poste)) {
            $query = $query
            ->andWhere('e.renderPoste = :val6')
            ->setParameter('val6', $poste);
        }

       # je vérifie si le role est renseigné
        if(!empty( $role)) {
            $query = $query
            ->andWhere('e.roles LIKE :val7')
            ->setParameter('val7', '%'.$role.'%');
        }

        $query = $query
        ->getQuery()
        ->getResult();

        return $query;
    }




    // /**
    //  * @return Employe[] Returns an array of Employe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Employe
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

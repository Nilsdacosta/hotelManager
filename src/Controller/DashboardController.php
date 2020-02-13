<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function checkInOut( ReservationRepository $reservationRepository,  EntityManagerInterface $entityManager, ChambreRepository $chambreRepository)
    {

        /* ************************
        |AFFICHAGE des CHECKIn/Out|
        **************************/

        # J'instance un nouvel objet date
        $date = new \DateTime;

        # je récupère les réservations dont la date d'arrivé est égale a la date du jour
        $reservationDuJour = $reservationRepository->findCheckin();

        # je récupère les réservations dont la date de départ est égale a la date du jour
        $departDuJour = $reservationRepository->findCheckOut();

        # je compte le nombre d'arrivé à la date du jour
        $nbResaJour = $reservationRepository->countin();

        # je compte le nombre de départ à la date du jour
        $nbDepartJour = $reservationRepository->countout();


        /* ************************
        |     EDIT DATE CHAMBRE   |
        **************************/

        /* REGLE :
            Si la réservation est validée (status = 2), l'état de la chambre change et passe en recouche (etat = 3)
            Si la réservation est facturé (status = 4), l'état' de la chambre change et passe en sale (etat = 1)
            Si al réservation est annulée (status = 3)
                -> au check in : l'état' de la chambre change et repasse en prête (etat = 3)
                -> au check out : l'état de la chambre change et repasse en sale (etat = 1)
        */

        # je teste les statuts de la réservation pour modifier l'état de la chambre en fonction de la date d'arrivée
        foreach ($reservationDuJour as $reservation) {
            if ($reservation->getStatus() == 2) {
                $etat = 3;

                # Pour chaque réservation, je récupère l'id des chambres afin de mettre à jour leur statut
                $chambres = $reservation->getChambre();

                foreach ($chambres as $chambre) {
                    $idChambre = $chambre->getId();
                    $findChambre = $chambreRepository->find($idChambre);
                    $findChambre->setEtat($etat);

                    // je procède à l'enregistrement de mes données
                    $entityManager->persist($findChambre);

                    // j'enregistre les données en BDD
                    $entityManager->flush();
                }


            } elseif ($reservation->getStatus() == 3) {
                $etat = 3;
                # Pour chaque réservation, je récupère l'id des chambres afin de mettre a jour leur statut
                $chambres = $reservation->getChambre();
                foreach ($chambres as $chambre) {
                    $idChambre = $chambre->getId();
                    $findChambre = $chambreRepository->find($idChambre);
                    $findChambre->setEtat($etat);

                    // je procède à l'enregistrement de mes données
                    $entityManager->persist($findChambre);

                    // j'enregistre les données en BDD
                    $entityManager->flush();
                }

            }


        }
        # je teste les statuts de la réservation pour modifier l'état de la chambre en fonction de la date de départ
        foreach ($departDuJour as $reservation) {

            if ($reservation->getStatus() == 3) {
                $etat = 1;
                # Pour chaque réservation, je récupère l'id des chambres afin de mettre a jour leur statut
                $chambres = $reservation->getChambre();
                foreach ($chambres as $chambre) {
                    $idChambre = $chambre->getId();
                    $findChambre = $chambreRepository->find($idChambre);
                    $findChambre->setEtat($etat);

                    // je procede a l'enregistrement de mes données
                    $entityManager->persist($findChambre);

                    // j'enregistre les données en BDD
                    $entityManager->flush();
                }


            } elseif ($reservation->getStatus() == 4) {
                $etat = 1;
                # Pour chaque réservation, je récupère l'id des chambres afin de mettre a jour leur statut
                $chambres = $reservation->getChambre();
                foreach ($chambres as $chambre) {
                    $idChambre = $chambre->getId();
                    $findChambre = $chambreRepository->find($idChambre);
                    $findChambre->setEtat($etat);

                    // je procede a l'enregistrement de mes données
                    $entityManager->persist($findChambre);

                    // j'enregistre les données en BDD
                    $entityManager->flush();
                }
            }
        }

        # je récupère les réservations 'actives' à la date du jour pour afficher un état des clients présents
        $reservationEtatDuJour = $reservationRepository->findResaDashboard($date);
      
        // Je retourne mes informations au template
        return $this->render('dashboard/index.html.twig', [
            'reservationDuJour' => $reservationDuJour,
            'date' => $date,
            'nbResaJour' => $nbResaJour,
            'nbDepartJour'=>$nbDepartJour,
            'departDuJour' => $departDuJour,
            'reservationEtatDuJour'=> $reservationEtatDuJour,
  

        ]);
    }


    public function calculGain($id, ReservationRepository $reservationRepository)
     {
         # Je récupère ma réservation via l'id
        $reservation = $reservationRepository->findAll();

        # je calcule le nombre de jour entre la date de Sortie et la date de début
        $nombreJours = $reservation->getDateEntree()->diff($reservation->getDateSortie());

        # je déclare 3 variables à 0 pour les calculs fais sur la page récapitulatif
        $total = 0;
        $totalOption = 0;
        $tva = 0;
        $tvaOption = 0;
        $ttc = 0;


        
        return $this->render('reservation/recapitulatif.html.twig', [
            'reservation'=> $reservation,
            'nombreJours'=> $nombreJours,
            'total'=> $total,
            'totalOption'=>$totalOption,
            'tvaOption'=>$tvaOption,
            'tva'=> $tva,
            'ttc'=>$ttc,
            
        ]);
     }

}

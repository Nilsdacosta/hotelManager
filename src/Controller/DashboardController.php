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
    
        ################################################################
    ###################### CHECKIN / CHECKOUT ######################
    ################################################################


    public function checkInOut( ReservationRepository $reservationRepository,  EntityManagerInterface $entityManager, ChambreRepository $chambreRepository)
    {
         // $reservationDuJour = $reservationRepository->findBy(['dateEntree' => $date],['dateEntree' => 'ASC'],5,0);

        $date = new \DateTime;
        $reservationDuJour = $reservationRepository->findCheckin();
        $departDuJour = $reservationRepository->findCheckOut();
        $nbResaJour = $reservationRepository->countin();
        $nbDepartJour = $reservationRepository->countout();


        ################################################################
        ################## UPDATE ETAT CHAMBRE #########################
        ################################################################

        foreach ($reservationDuJour as $reservation) {
            // si le status de la réservation est  validée (2) j'update l'état de la chambre en sale (4)
            if ($reservation->getStatus() == 2) {
                $etat = 4;
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


                // si le statut de la réservation est  facturée (4) j'update l'état de la chambre en sale (1)
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

        foreach ($departDuJour as $reservation) {
            // si le status de la réservation est  validée (2) j'update l'état de la chambre en sale (4)
            if ($reservation->getStatus() == 2) {
                $etat = 4;
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


                // si le statut de la réservation est  facturée (4) j'update l'état de la chambre en sale (1)
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

        # je récupère les réservations 'active' à la date du jour pour afficher un état des clients présents
        $reservationEtatDuJour = $reservationRepository->findResaDashboard($date);
      
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

<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
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


    public function checkInOut( ReservationRepository $reservationRepository, Request $request, EntityManagerInterface $entityManager, ChambreRepository $chambreRepository)
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
}

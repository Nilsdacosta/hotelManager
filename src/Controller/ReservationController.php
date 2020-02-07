<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\CheckInType;
use App\Entity\Reservation;
use App\Form\ReservationClientType;
use App\Repository\ClientRepository;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
        /**
         * @Route("/reservation", name="reservation")
         * @Route("/reservation/edit/{id}", name="editReservation")
         * @Route("/reservation/edit/{idResa}/client/{id}", name="editClient")
         */
    public function index(ReservationRepository $reservationRepository,Request $request, EntityManagerInterface $entityManager, $id=null, ClientRepository $clientRepository, $idResa=null)
    {
      ################################################################
      ##################### FORMULAIRE RESA  #########################
      ################################################################

        if ($id !== null ) {
            $reservation = $reservationRepository->find($id);
        }else {
            $reservation = new Reservation();
        }


        // je genere le formulaire Réservation
        $form = $this->createForm(ReservationClientType::class, $reservation);

        // je recupere les données du form
        $form->handleRequest($request);

        // si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setStatus(1);
            $reservation->setDateCreation(new \DateTime);

            // je procede a l'enregistrement de mes données

            $entityManager->persist($reservation);

            // j'enregistre les données en BDD
            $entityManager->flush();

            

            // // je redirige vers la page de l'annonce
            

            
            return $this->redirectToRoute('reservationRecapitulatif', [
                'id' => $reservation->getId()
            ]);
                
            
            
        }


        
        

        ################################################################
        ##################### FORMULAIRE CLIENT  #######################
        ################################################################

        if ($id !== null ) {
            $Client = $clientRepository->find($id);
        }else {
            $Client = new Client();
        }


        // je genere le formulaire ajout client
        $formClient = $this->createForm(ClientType::class, $Client);

        // je recupere les données du form
        $formClient->handleRequest($request);

        // si les données sont valides
        if ($formClient->isSubmitted() && $formClient->isValid()) {
        

            // je procede a l'enregistrement de mes données
            //$Client->setCreatedAt( new \DateTime);

            $entityManager->persist($Client);

            // j'enregistre les données en BDD
            $entityManager->flush();

            // j'ajoute un message flash pour alerter le user
            $this->addFlash(
                'ajoutClient',
                'Le client a bien été ajoutée'
            );

            if($id !=null){
                return $this->redirectToRoute('reservationRecapitulatif', [
                    'id' => $idResa
                ]);

            }

        
        }

        return $this->render('reservation/index.html.twig', [
            'formClient' => $formClient->createView(),
            'formResa' => $form->createView()
        ]);


    }


    /**
     * @Route("/reservation/{id}/recapitulatif", name="reservationRecapitulatif")
     * 
     */

     public function showRecapitulatif($id, ReservationRepository $reservationRepository)
     {
        $reservation = $reservationRepository->find($id);
     
        $nombreJours = $reservation->getDateEntree()->diff($reservation->getDateSortie());

        $total = 0;
        $tva = 0;
        $ttc = 0;
        return $this->render('reservation/recapitulatif.html.twig', [
            'reservation'=> $reservation,
            'nombreJours'=> $nombreJours,
            'total'=> $total,
            'tva'=> $tva,
            'ttc'=>$ttc
        ]);
     }

    




    



    /**
     * @Route("/reservation/timeline", name="reservationTimeline")
     */
    public function showCalendar(ReservationRepository $reservationRepository)
    {
        
      ################################################################
      ################## AFFICHAGE FULLCALENDAR ######################
      ################################################################



      # je crée la requete pour récupérer les info de la table reservation
        // j'ai crée une requete spéciale dans Rservation repository. la valeur sélectionnée est celle exclue de la requete
        $findReservations = $reservationRepository->findAll()/*findReservation(3)*/;
        $tabEvents = array();
        // sleep(1);
        
        foreach ($findReservations as $findReservation) {
            //debug($findReservations);
            $chambres=$findReservation->getChambre();
            foreach($chambres as $chambre){
                $event = new Reservation();
                //$event->start=$findReservation->getDateEntreepourCalendrier();
                //$event->end=$findReservation->getDateSortiePourCalendrier();
                $event->start='2020-02-01';
                $event->end='2020-02-10';
                $event->rendering='background';
                $event->backgroundColor='#FE1919';
                $event->idChambre = $chambre->getId();
                $event->idReservation=$findReservation->getId();
            }
            
                   
      
            
            array_push($tabEvents, $event);
        }

        return  new JsonResponse($tabEvents);

        // return $this->render('reservation/index.html.twig', [
        //     'controller_name' => 'ReservationController',
        // ]);
        //echo json_encode($tabEvents);


    }


    /**
     * @Route("/reservation/check", name="reservationCheck")
     * 
     */

    public function checkInOut( ReservationRepository $reservationRepository, Request $request, EntityManagerInterface $entityManager)
    {
        
        $date = new \DateTime;
        $reservationDuJour = $reservationRepository->findCheckin();
        
            return $this->render('reservation/check.html.twig', [
                'reservationDuJour'=> $reservationDuJour,
                'date'=> $date,
              
            ]);
    }


    /** 
     * @Route("/reservation/{id}/check/form", name="reservationCheckInForm")
     */
    public function formCheckIn(ReservationRepository $ReservationRepository, Request $request, EntityManagerInterface $entityManager,ChambreRepository $chambreRepository,$id=null)
    {
        if($id!=null){

       
            $reservation = $ReservationRepository->find($id);

            // je genere le formulaire Réservation checkIn
            $formCheckIn = $this->createForm(CheckInType::class, $reservation);

            // je recupere les données du form
            $formCheckIn->handleRequest($request);

            // si les données sont valides
            if ($formCheckIn->isSubmitted() && $formCheckIn->isValid()) {

                // je procede a l'enregistrement de mes données

                $entityManager->persist($reservation);

                // j'enregistre les données en BDD
                $entityManager->flush();

                // j'ajoute un message flash pour alerter le user
                $this->addFlash(
                    'checkInResa',
                    'La réservation a bien été validée'
                );

                // // je redirige vers la page de l'annonce
                

                
                return $this->redirectToRoute('reservationCheck');


                ################################################################
                ################## UPDATE ETAT CHAMBRE #########################
                ################################################################

                
                // si le status de la réservation est  validée (2) j'update l'état de la chambre en sale (4)
                if($reservation->getStatus()==2){
                    $etat = 4;
                    
                // si le statu de la réservation est  facturée (4) j'update l'état de la chambre en sale (1)
                }elseif($reservation->getStatus()==4){
                    $etat = 1;
                }
                $chambres= $reservation->getChambre();
                foreach($chambres as $chambre){
                    $idChambre= $chambre->getId();
                    $findChambre = $chambreRepository->findBy(["id"=>$idChambre],[]);
                    $findChambre->setEtat($etat);
            
                    // je procede a l'enregistrement de mes données
                    $entityManager->persist($findChambre);

                    // j'enregistre les données en BDD
                    $entityManager->flush();
                }
                   
            }

            return $this->render('reservation/formCheckIn.html.twig', [
                'reservation' => $formCheckIn->createView(),
                
            ]);
        }
    }
    
}

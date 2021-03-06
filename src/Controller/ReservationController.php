<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\CheckInType;
use App\Form\CheckOutType;
use App\Entity\Reservation;
use App\Form\HistoriqueResaType;
use App\Form\ReservationClientType;
use App\Repository\ClientRepository;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Repository\OptionServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     * @Route("/reservation/edit/resa/{id}", name="editReservation")
     * @Route("/reservation/edit/client/{id}", name="editClient")
     * @param ReservationRepository $reservationRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param null $id
     * @param ClientRepository $clientRepository
     * @param null $idResa
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function index(ReservationRepository $reservationRepository, OptionServiceRepository $optionServiceRepository, Request $request, EntityManagerInterface $entityManager, $id=null, ClientRepository $clientRepository, $idResa=null)
    {
        /* ************************
        | FORMULAIRE RESERVATION  |
        **************************/

        # je test s'il s'agit d'une nouvelle réservation ou d'ume modification
        if($request->query->get('idResa')){
            $reservation = $reservationRepository->find($request->query->get('idResa'));
        }elseif ($id != null ) {
                $reservation = $reservationRepository->find($id);
        }else {
            $reservation = new Reservation();
        }


        // je génère le formulaire Réservation, je peux modifier le formulaire uniquement s'il n'est pas facturé
        if($reservation->getStatus() != 4)
        {
            # je crée un formulaire
            $form = $this->createForm(ReservationClientType::class, $reservation);

            // je recupère les données du formulaire
            $form->handleRequest($request);

            // si les données sont valides
            if ($form->isSubmitted() && $form->isValid()) {
                $reservation->setStatus(1);
                $reservation->setDateCreation(new \DateTime);

                # je check si au moins une option service a été coché, sinon je lui donne une valeur par défault
                if(null !=($reservation->getOptionService())){
                    $option = $optionServiceRepository->findOneBy(['id'=>1]);
                    $reservation->addOptionService($option);
                }


                // je procède à l'enregistrement de mes données
                $entityManager->persist($reservation);

                // j'enregistre les données en BDD
                $entityManager->flush();

                // // je redirige vers la page de l'annonce
                return $this->redirectToRoute('reservationRecapitulatif', [
                    'id' => $reservation->getId()
                ]);
            }
        }
        
        /* ************************
        |    FORMULAIRE CLIENT     |
        **************************/

        # je teste s'il s'agit d'un nouveau client ou d'ume modification
        
        if($request->query->get('idClient')){
            $Client = $clientRepository->find($request->query->get('idClient'));
        }elseif ($id !== null ) {
            $Client = $clientRepository->find($id);
        }else {
            $Client = new Client();
        }


        // je gégère le formulaire ajout client
        $formClient = $this->createForm(ClientType::class, $Client);

        // je recupère les données du formulaire
        $formClient->handleRequest($request);

        // si les données sont valides
        if ($formClient->isSubmitted() && $formClient->isValid()) {

            // je procède à l'enregistrement de mes données
            $entityManager->persist($Client);

            // j'enregistre les données en BDD
            $entityManager->flush();

            // j'ajoute un message flash pour alerter le user
            $this->addFlash(
                'ajoutClient',
                'Le client a bien été ajoutée'
            );

            # s'il s'agit d'une modification je redirige vers la page récapitulatif
            if($id !=null){
                return $this->redirectToRoute('reservationRecapitulatif', [
                    'id' => $reservation->getId() 
                ]);

            }
        }

        // Je retourne mes informations au template
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

        /* *************************
        | RECAPITULATIF RESERVATION |
        ****************************/


         # Je récupère ma réservation via l'id
        $reservation = $reservationRepository->find($id);

        # je calcule le nombre de jour entre la date de Sortie et la date de début
        $nombreJours = $reservation->getDateEntree()->diff($reservation->getDateSortie());

        # je déclare 3 variables à 0 pour les calculs fais sur la page récapitulatif
        $total = 0;
        $totalOption = 0;
        $tva = 0;
        $tvaOption = 0;
        $ttc = 0;


        if($reservation->getStatus() == 4){
            $document = "Facture";
            $date = new \Datetime;
        }else{
            $document = "Récapitulatif";
            $date = $reservation->getDateCreation();
        }

        // Je retourne mes informations au template
        return $this->render('reservation/recapitulatif.html.twig', [
            'reservation'=> $reservation,
            'nombreJours'=> $nombreJours,
            'total'=> $total,
            'totalOption'=>$totalOption,
            'tvaOption'=>$tvaOption,
            'tva'=> $tva,
            'ttc'=>$ttc,
            'document'=>$document,
            'date'=> $date
        ]);
     }


    /**
     * @Route("/reservation/timeline", name="reservationTimeline")
     */
    public function showCalendar(ReservationRepository $reservationRepository)
    {
        
        /* ************************
        |  AFFICHAGE FULLCALENDAR  |
        **************************/

      # je crée la requête pour récupérer les infos de la table réservation
        // j'ai créée une requête spéciale dans Réservation repository. La valeur sélectionnée est celle exclue de la requete
        $findReservations = $reservationRepository->findReservation(3);

        # je crée un tableau d'évènement qui sera envoyé via json pour le fullcalendar   
        $tabEvents = array();
        
        foreach ($findReservations as $findReservation) {
            //debug($findReservations);
            $chambres=$findReservation->getChambre();
            foreach($chambres as $chambre){
                $event = new Reservation();
                $event->start=$findReservation->getDateEntreepourCalendrier()->format('Y-m-d');
                $event->end=$findReservation->getDateSortiePourCalendrier()->format('Y-m-d');
                $event->rendering='background';
                $event->backgroundColor='#FE1919';
                $event->idChambre = $chambre->getId();
                $event->idReservation=$findReservation->getId();
                array_push($tabEvents, $event);
            }
        }

        return  new JsonResponse($tabEvents);

    }





      ################################################################
      ################## AFFICHAGE HISTORIQUE ######################
      ################################################################


    /**
     * @Route("/historique/", name="historique_resa", methods={"GET"})
     * @Route("/historique/capacite/{id}", name="historique_resa_capacite", methods={"GET"})
     */
    public function historique(ReservationRepository $reservationRepository,Request $request, ClientRepository $clientRepository, ChambreRepository $chambreRepository, OptionServiceRepository $optionServiceRepository, $id=null): Response
    {
        


        # je défini les requetes pour l'affichage du formulaire de filtre

        $idReservations = $reservationRepository->findAllGroupeBy('id');
        $statusResa=$reservationRepository->findAllGroupeBy('status');

        $capaciteChambre = $chambreRepository->findAllGroupeBy('capacite');
        $nomChambre=$chambreRepository->findAllGroupeBy('nom');
      


        $nomClient = $clientRepository->findAllGroupeBy('nom');
        $prenomClient = $clientRepository->findAllGroupeBy('prenom');


        $optionResa = $optionServiceRepository->findAllGroupeBy('nomOption');

        # je récupère les données envoyées via le get
        $request = Request::createFromGlobals();
        $idResaRequest= $request->query->get('idResa');
        $capaciteRequest = $request->query->get('capacite');
        $dateCreationRequest = $request->query->get('dateCreation');
        $nomChambreRequest = $request->query->get('nomChambre');
        $nomClientRequest = $request->query->get('nomClient');
        $prenomClientRequest = $request->query->get('prenomClient');

        if($request->query->get('statusResa') == "Réservée"){
            $statusResaRequest= 1;
         }elseif($request->query->get('statusResa')=="Validée"){
            $statusResaRequest= 2;
         }elseif($request->query->get('statusResa')=="Annulée"){
            $statusResaRequest= 3;
         }elseif($request->query->get('statusResa')=="Facturée"){
            $statusResaRequest= 4;
         }else{
             $statusResaRequest= '';
        }

        $dateEntreeRequest = $request->query->get('dateEntree');
        $dateSortieRequest = $request->query->get('dateSortie');
        $optionServiceRequest = $request->query->get('optionService');


        # Affichage des données, je teste si le filtre a été envoyé ou non
        if(!empty($request)){
            $reservations=$reservationRepository->historiqueResaFiltre($idResaRequest, $capaciteRequest, $dateCreationRequest,$nomChambreRequest,$nomClientRequest,$prenomClientRequest,$statusResaRequest,$dateEntreeRequest, $dateSortieRequest,$optionServiceRequest);

        }else{
            $reservations=$reservationRepository->findBy([],['dateCreation'=>'DESC']);

        }


        return $this->render('reservation/historique.html.twig', [
            'reservations' => $reservations,
            'optionResa' => $optionResa,
            'idReservations' =>$idReservations,
            'capaciteChambre'=>$capaciteChambre,
            'nomChambre'=> $nomChambre,
            'nomClient'=> $nomClient,
            'prenomClient'=>$prenomClient,
            'statusResa'=> $statusResa,


        ]);
    }

 


    ################################################################
    ###################### CHECKIN / CHECKOUT ######################
    ################################################################



    /** 
     * @Route("/reservation/check", name="reservationCheck")
     */
    public function checkInOut( ReservationRepository $reservationRepository, Request $request, EntityManagerInterface $entityManager, ChambreRepository $chambreRepository)
    {

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
                $etat = 2;
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
                $etat = 2;
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

        return $this->render('reservation/check.html.twig', [
            'reservationDuJour' => $reservationDuJour,
            'date' => $date,
            'departDuJour' => $departDuJour,
            'nbResaJour' => $nbResaJour,
            'nbDepartJour'=>$nbDepartJour,

        ]);
    }





   /**
     * @Route("/reservation/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }






     /** 
     * @Route("/reservation/checkin", name="reservationCheckIn")
     */
    public function reservationCheckIn(ReservationRepository $reservationRepository, Request $request, EntityManagerInterface $entityManager,ChambreRepository $chambreRepository,$id=null)
    {
        
        $id=$request->get('id');
        $etat=$request->get('etat');

        $reservation= $reservationRepository->findOneBy(['id'=> $id]);

        $reservation->setStatus($etat);
         // je procède à l'enregistrement de mes données
         $entityManager->persist($reservation);

         // j'enregistre les données en BDD
         $entityManager->flush();

            # je renvoie a la page formCheckIn le formulaire de checkout
            return new Response($reservation->getRenderStatus());

    }
    
}

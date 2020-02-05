<?php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    require_once("inc/init.php");

    



      ################################################################
      ################## AFFICHAGE FULLCALENDAR ######################
      ################################################################



      # je crée la requete pour récupérer les info de la table booked
      $req_findBooked = "SELECT * FROM reservation ";
      $pdoST_find_booked = $pdo->prepare($req_findBooked);

      if($pdoST_find_booked->execute()){
          //debug(json_encode($pdoST_find_booked->fetchAll())) ;
          //json_encode($pdoST_find_booked->fetchAll());

          //debug(json_decode($json));

          $findbookeds=$pdoST_find_booked->fetchAll();
          $tabEvents = array();
          sleep(1);
           foreach ($findbookeds as $findbooked) {
               //debug($findbookeds);
               $event = new Reservation();
               $event->start=$findbooked['date_arrival'];
               $event->end=$findbooked['date_departure'];
               $event->rendering='background';
               $event->backgroundColor='#FE1919';
               $event->idarticle=$findbooked['fk_id_article'];
               $event->idbooked=$findbooked['id_booked'];
              
               array_push($tabEvents, $event);
           }
           echo json_encode($tabEvents);
      }




    # je gère de délais de chargement de mes infos
    
   



?>
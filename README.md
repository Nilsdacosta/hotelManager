# hotelManager

Projet réalisé par Audrey Thirard, Virginie Hangora, Valentin Proton et Nils Da Costa :)

Fin de projet après trois mois de formation à WebForce3.

Les technologies utilisés sont :
- Symfony: POO, CRUD
- Librairie FullCalendar
- Librairie Ajax/jquery
- Git

Développé sur VSC

Ce projet développé sur 7 jours vient conclure 3 mois de formation.

Il répond à la problématique de la gestion hotelière.


*********************************************************
|                           |
|             Règles à suivre ?           |
|                           |
*********************************************************
        Nom des routes      +   Nom au format camelCase ou avec _ à définir
C : CREATE      new
R : READ        show
U : UPDATE      edit
D : DELETE      delete
*********************************************************
|                           |
|               Entity              |
|                           |
*********************************************************
Création des entités
Prioritaire :
    TABLE  |  EN REL. AVEC TABLE   |TYPE RELAITION   |  COMMENTAIRES
    Chambre
        -> relation TVA      /ManyToOne
    Client
    Employe
        -> relation DroitAdmin   /ManyToOne  /!\ seulement so droitAdmin existe
    Réservation
        -> relation Client   /ManyToOne
        -> relation Option service /manyToMany
    Assignation
        -> relation Employe  /ManyToOne
        -> relation Chambre  /ManyToOne
        -> relation OptionServ.  /ManyToOne
    Option Service
        -> relation Employé /ManyToOne
        -> relation Tva      /ManyToOne
    Detail resa chambre
        -> relation Chambre  /ManyToOne
        -> relation Reservation  /ManyToOne
/!\ detail option resa : sera une table créer par symfony, relation manytomany entre réservation et option service
Secondaire : 
    photo chambre
        -> relation Chambre  /ManyToOne
    facture
        -> relation Reservation  /ManyToOne
        -> relation Établisst   /ManyToOne      Si facture existe et si on a le temps
    établissement                      
    tva
    Droit admin
    
*********************************************************
|                           |
|            RECEPTIONNISTE         |
|                           |
*********************************************************
Page 1 :Calendrier
    @Route : /reservation
    Entity:  - chambre
         - client
         - Resa
    R : Affichier les dispo des chambres
         ->  entity resa
    R : Afficher le calendrier
         -> chambre
    U C : Formulaire réservation
    U C : Formulaire client
            - si n'existe pas, formulaire d'ajout
            - recherche intuitive si existe
    Une fois la réservation validé par le client
            -> statu réservation : réservé
        renvoie vers page récap
    -------------------------------
Page 2: page recap 
    @Route : /reservation/recapitulatif         => pour la création C
    @Route : /reservation/recapitulatif/{id}    => pour la modification/consultation R/U/D
    Entity:
        - Résa
        - Client
        - Chambre
    - création d'un template de récap (le meme que facture)
        - renvoi sur formulaire si modification (Page1)
        -> pas de modification possible
        - impression pour récap et envoi client
    -------------------------------
Page 3 : historique résa
    @Route : /reservation/all
    Entity : chambre
        - resa
            - client
    n° résa | date arrivé | date départ | client | n°chambre | N° fact
            -> Lien sur N° résa pour renvoi vers' Page récap' (page 2 @Route : /reservation/recapitulatif/{id})
        -> Lien sur N° fac pour renvoi vers' Page facture' (page ?  @Route : /facturation)
            
    R : Formulaire pour filtrer
    -------------------------------
Page 3.1    Page client
    Entity: - client
        R : Afficher les informations clients
        U : Lien renvoie 'page 1' pour update ou créer une page update spéciale?
    -------------------------------
Page 4 : checkin/checkout
    @Route : /check
    Entity : chambre
        - resa
            - client
    
    R Affichage des check in
            - filtre date début = date du jour
            - formulaire de validation du checkIn :
                -> U Réservation, statut = 'validé'
                -> U Chambre en statut 'sale '
            -> U réservation :  acompte versé?
    R Affichage Check out
            -> date de fin= date du jour
            
            - formulaire de validation du CheckOut :
                -> U Réservation, statut = 'validé'
            -> U réservation : solde payé a rajouter sur acompte versé?
                -> U Chambre en statut 'sale '   
        - Lancement de la facturation facturation
            C : création de la facture
*********************************************************
|                           |
|             LA GOUVERNANCE            |
|                           |
*********************************************************
Page 5 Planning quotidien des femmes de ménage
    @Route : /gouvernance/assignation       => pour valider les assignations
    @Route : /gouvernance/assignation/{id}      => pour valider le contrôle de la gouvernante, et MàJ des statut chambre
    Entity :
        - Chambre
        - Option service
        - employé
        - Assignation
        
        
    Chambre | Statut | Options (checkbox) | employé (select) | nouveau statut (select)
    R : toutes les Chambres + statut | option service dispo | employé dont role est ménage | nouveau statut 
    
    -Validation des assignation
        -> C : création des assignations
    - Validation controle du travail par la gouvernante
        -> U: chambre statut
    -------------------------------
Page 6 Historique des assignations
    @Route : /gouvernance/assignation/all
    Entity :
        - Chambre
        - Option service
        - employé
        - Assignation
    id Assignation | date | chambre | employee assignée
    R: affichage
    R : formulaire/filtre de recherche
    Lien sur id Assignation pour ouvrir le planning de la journée (Page 5)
*********************************************************
|                           |
|               MANAGER             |
|                           |
*********************************************************
Page 7  Création employe
    @Route : /employee/new      
    @Route : /employee/edit
    @Route : /employee/all      => pour voir tous les employés
    @Route : /employee/delete
    Entity : Employé
        R : liste employé
        C : rajouter employé
        U : modifier employé
        D : suppression employé
    -------------------------------
Page 8  Création option
    @Route : /option/new        
    @Route : /option/edit
    @Route : /option/all        => pour voir toutes les options
    @Route : /option/delete
    
    Entity : Option service
        R : liste option
        C : rajouter option
        U : modifier option
        D : suppression option
    -------------------------------
Page 9  Création chambre
    @Route : /chambre/new       
    @Route : /chambre/edit
    @Route : /chambre/all       => pour voir toutes les chambres (sans calendrier)
    @Route : /chambre/delete
    Entity : Option Chambre
        R : liste chambre
        C : rajouter chambre
        U : modifier chambre
        D : suppression chambre 
    -------------------------------
Page 10 Dashboard
        R: que mettre dedant
*********************************************************
|                           |
|               GENERAL     |
|                           |
*********************************************************
Page 11 Connexion
    Page de connexion

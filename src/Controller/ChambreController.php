<?php
namespace App\Controller;
use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/admin/chambre")
 */
class ChambreController extends AbstractController
{
    /**
     * @Route("/", name="chambre_index", methods={"GET"})
     */
    public function index(ChambreRepository $chambreRepository): Response
    {

        /* ***********************
        | HISTORIQUE DES CHAMBRES |
        *************************/


        # je défini des variables liées aux requêtes des différents repository pour l'affichage du formulaire de filtre 
        $idChambre = $chambreRepository->findAllGroupeBy('id');
        $capaciteChambre = $chambreRepository->findAllGroupeBy('capacite');
        $etatChambre = $chambreRepository->findAllGroupeBy('etat');
        $descriptionChambre = $chambreRepository->findAllGroupeBy('description');
        $prixChambre = $chambreRepository->findAllGroupeBy('prix');
        $nomChambre = $chambreRepository->findAllGroupeBy('nom');

        # je récupère les données envoyées via le get
        $request = Request::createFromGlobals();
        $idChambreRequest= $request->query->get('id');
        $capaciteChambreRequest= $request->query->get('capacite');
        $etatChambreRequest= $request->query->get('etat');
        $descriptionChambreRequest= $request->query->get('description');
        $prixChambreRequest= $request->query->get('prix');
        $nomChambreRequest= $request->query->get('nom');

        #Si j'ai un retour en Get , j'utilise la requete de filtre, sinon j'affiche tout
        if(!empty($request)){ dump($request);
            $chambres =$chambreRepository->chambreFiltre($idChambreRequest,$capaciteChambreRequest,$etatChambreRequest,$descriptionChambreRequest,$prixChambreRequest,$nomChambreRequest);
        }else{
            $chambres =$chambreRepository->findAll();
        }

        // Je retourne mes informations au template
        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambres,
            'idChambre'=>$idChambre,
            'capaciteChambre'=> $capaciteChambre,
            'etatChambre'=> $etatChambre,
            'descriptionChambre'=> $descriptionChambre,
            'prixChambre'=> $prixChambre,
            'nomChambre'=> $nomChambre,
            'nomChambreRequest'=>$nomChambreRequest
        ]);
    }


    /**
     * @Route("/new", name="chambre_new", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function new(Request $request): Response
    {

        /* ************************
        | AJOUT/EDIT DES CHAMBRES |
        **************************/

        # Ji'nstance un nouvel objet chambre
        $chambre = new Chambre();
        # je lui assigne un statut
        $chambre->setStatutAssignationMenage(0);

        # je crée un formulaire
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        # si le formulaire est soumis et valid j'enregistre en BDD et je redirige vers la page historique
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chambre);
            $entityManager->flush();

            return $this->redirectToRoute('chambre_index');
        }
        
    
        // Je retourne mes informations au template    
        return $this->render('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="chambre_show", methods={"GET"})
     */
    public function show(Chambre $chambre): Response
    {

        /* ************************
        | AFFICHAGE DE LA CHAMBRE |
        **************************/

        // Je retourne mes informations au template
        return $this->render('chambre/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }




    /**
     * @Route("/{id}/edit", name="chambre_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, Chambre $chambre): Response
    {

        
        /* ************************
        |    EDIT DE LA CHAMBRE    |
        **************************/

        // Je crée un formulaire 
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        # si le formulaire est soumis et valid j'enregistre en BDD et je redirige vers la page historique
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('chambre_index');
        }

        // Je retourne mes informations au template
        return $this->render('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="chambre_delete", methods={"DELETE"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Request $request, Chambre $chambre): Response
    {

        /* *************************
        | SUPRRESSION DES CHAMBRES |
        ***************************/

        # Je supprime la donnée via csrfToken
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chambre);
            $entityManager->flush();
        }

        // Je redirige vers l'historique des chambres
        return $this->redirectToRoute('chambre_index');
    }



    /**
     * @Route("/reservation/chambre", name="reservationChambre")
     */
    public function findChambreForCalendar(ChambreRepository $chambreRepository)
    {

        /* ***********************
        |  FULLCALENDAR CHAMBRES |
        *************************/

        
        # jé récupère toutes les chambres 
        $findChambres=$chambreRepository->findAll();

        # je créée un tableau dans lequel je vais mettre chaque chambre
        $tabChambre = array();
        foreach($findChambres as $chambres){
            $chambre= new Chambre();
            $chambre->idChambre = $chambres->getId();
            $chambre->building="Catégorie : ".$chambres->getCapacite();
            $chambre->title= ($chambres->getNom().' - '.$chambres->getCapacite().' - '. ($chambres->getPrix()*(1+ ($chambres->getTva()->getTaux())/100).'euros TTC' ));
            array_push($tabChambre, $chambre);
        }

        # j'envoie en JSON
        return  new JsonResponse($tabChambre);
    }
}
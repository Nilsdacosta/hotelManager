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
        # je défini les requetes pour l'affichage du formulaire de filtre
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

         if(!empty($request)){
            $chambres =$chambreRepository->chambreFiltre($idChambreRequest,$capaciteChambreRequest,$etatChambreRequest,$descriptionChambreRequest,$prixChambreRequest,$nomChambreRequest);
         }else{
            $chambres =$chambreRepository->findAll();
         }

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
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chambre);
            $entityManager->flush();

            return $this->redirectToRoute('chambre_index');
        }

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
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chambre_index');
        }

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
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chambre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chambre_index');
    }


    
    /**
     * @Route("/reservation/chambre", name="reservationChambre")
     * 
     */
    public function findChambreForCalendar(ChambreRepository $chambreRepository)
    {
        $findChambres=$chambreRepository->findAll();
        $tabChambre = array();

        foreach($findChambres as $chambres){
            $chambre= new Chambre();
            $chambre->idChambre = $chambres->getId();
            $chambre->building="Catégorie : ".$chambres->getCapacite();
            $chambre->title= ($chambres->getNom().' - '.$chambres->getCapacite().' - '. ($chambres->getPrix()*(1+ ($chambres->getTva()->getTaux())/100).'euros TTC' ));
            array_push($tabChambre, $chambre);
        }
      
        return  new JsonResponse($tabChambre);

    }
}

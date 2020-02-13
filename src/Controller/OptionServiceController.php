<?php

namespace App\Controller;

use App\Entity\OptionService;
use App\Form\OptionServiceType;
use App\Repository\OptionServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/option")
 */
class OptionServiceController extends AbstractController
{
    /**
     * @Route("/", name="option_service_index", methods={"GET"})
     */
    public function index(OptionServiceRepository $optionServiceRepository): Response
    {

        
        /* ************************
        |     AFFICHAGE OPTION    |
        **************************/

        # je défini les requetes pour l'affichage du formulaire de filtre
        $idOption = $optionServiceRepository->findAllGroupeBy('id');
        $nomOption = $optionServiceRepository->findAllGroupeBy('nomOption');
        $prixOption = $optionServiceRepository->findAllGroupeBy('prixOption');

        # je récupère les données envoyées via le get
        $request = Request::createFromGlobals();
        $idOptionRequest= $request->query->get('id');
        $nomOptionRequest= $request->query->get('nom');
        $dateCreationOptionRequest= $request->query->get('date');
        $prixOptionRequest= $request->query->get('prix');
        
        # Affichage des données, je teste si le filtre a été envoyé ou non
        if(!empty($request)){
            $optionService =$optionServiceRepository->optionFiltre($idOptionRequest,$nomOptionRequest,$dateCreationOptionRequest,$prixOptionRequest);    
        }else{
             $optionService =$optionServiceRepository->findAll();    
        }

        // Je retourne mes informations au template
        return $this->render('option_service/index.html.twig', [
            'option_services' => $optionService,
            'idOption'=>$idOption,
            'nomOption'=>$nomOption,
            'prixOption'=>$prixOption
        ]);
    }

    /**
     * @Route("/new", name="option_service_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        /* ************************
        |      AJOUTER OPTION     |
        **************************/

        # j'instance un nouvel objet OptionService
        $optionService = new OptionService();

        # je crée un formulaire
        $form = $this->createForm(OptionServiceType::class, $optionService);
        $form->handleRequest($request);

        $user = $this->getUser();
        
        # si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            # j'enregistre en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $optionService->setDateCreation(new \DateTime());
            $optionService->setEmploye($user);
            $entityManager->persist($optionService);
            $entityManager->flush();

            # je redirige vers l'historique des options
            return $this->redirectToRoute('option_service_index');
        }


        // Je retourne mes informations au template
        return $this->render('option_service/new.html.twig', [
            'option_service' => $optionService,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="option_service_show", methods={"GET"})
     */
    public function show(OptionService $optionService): Response
    {

        /* ************************
        |    AFFICHER UNE OPTION   |
        **************************/

        // Je retourne mes informations au template
        return $this->render('option_service/show.html.twig', [
            'option_service' => $optionService,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="option_service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OptionService $optionService): Response
    {

        /* ************************
        |        EDIT OPTION       |
        **************************/

        # je crée un formulaire
        $form = $this->createForm(OptionServiceType::class, $optionService);
        $form->handleRequest($request);

        # si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            # j'enregistre en BDD
            $this->getDoctrine()->getManager()->flush();

            # je redirige vers l'historique des options
            return $this->redirectToRoute('option_service_index');
        }

        // Je retourne mes informations au template
        return $this->render('option_service/edit.html.twig', [
            'option_service' => $optionService,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="option_service_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OptionService $optionService): Response
    {

        /* ************************
        |    SUPPRESSION OPTION    |
        **************************/

        # je supprime avec csrfToken
        if ($this->isCsrfTokenValid('delete'.$optionService->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($optionService);
            $entityManager->flush();
        }

        // Je retourne mes informations au template
        return $this->redirectToRoute('option_service_index');
    }
}

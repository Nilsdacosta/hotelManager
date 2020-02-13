<?php

namespace App\Controller;

use App\Entity\Tva;
use App\Form\TvaType;
use App\Repository\TvaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tva")
 */
class TvaController extends AbstractController
{
    /**
     * @Route("/", name="tva_index", methods={"GET"})
     */
    public function index(TvaRepository $tvaRepository): Response
    {

        
        /* ************************
        |    AFFICHAGE TVA        |
        **************************/

        // Je retourne mes informations au template
        return $this->render('tva/index.html.twig', [
            'tvas' => $tvaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tva_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        
        /* ************************
        |         AJOUT TVA        |
        **************************/

        # j'instance un nouvel objet TVA
        $tva = new Tva();

        # je crée le formulaire
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        # si le formulaire et soumis et valid
        if ($form->isSubmitted() && $form->isValid()) {
            # j'enregistre en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tva);
            $entityManager->flush();

            # je redirige vers l'historique TVA
            return $this->redirectToRoute('tva_index');
        }

        // Je retourne mes informations au template
        return $this->render('tva/new.html.twig', [
            'tva' => $tva,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tva_show", methods={"GET"})
     */
    public function show(Tva $tva): Response
    {
        /* ************************
        |   AFFICHAGE D'UNE TVA    |
        **************************/


        // Je retourne mes informations au template
        return $this->render('tva/show.html.twig', [
            'tva' => $tva,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tva_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tva $tva): Response
    {

        /* ************************
        |   MODIFICATION TVA       |
        **************************/

        # je crée le formulaire
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        # si le formulaire et soumis et valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            # je redirige vers l'historique TVA
            return $this->redirectToRoute('tva_index');
        }


        // Je retourne mes informations au template
        return $this->render('tva/edit.html.twig', [
            'tva' => $tva,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tva_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tva $tva): Response
    {

        /* ************************
        |    SUPPRESSION TVA      |
        **************************/

        # je supprime via csrfToken
        if ($this->isCsrfTokenValid('delete'.$tva->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tva);
            $entityManager->flush();
        }


        // Je retourne mes informations au template
        return $this->redirectToRoute('tva_index');
    }
}

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
 * @Route("/option")
 */
class OptionServiceController extends AbstractController
{
    /**
     * @Route("/", name="option_service_index", methods={"GET"})
     */
    public function index(OptionServiceRepository $optionServiceRepository): Response
    {
        return $this->render('option_service/index.html.twig', [
            'option_services' => $optionServiceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="option_service_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $optionService = new OptionService();
        $form = $this->createForm(OptionServiceType::class, $optionService);
        $form->handleRequest($request);
        $user = $this->getUser();
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $optionService->setDateCreation(new \DateTime());
            $optionService->setEmploye($user);
            $entityManager->persist($optionService);
            $entityManager->flush();

            return $this->redirectToRoute('option_service_index');
        }

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
        return $this->render('option_service/show.html.twig', [
            'option_service' => $optionService,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="option_service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OptionService $optionService): Response
    {
        $form = $this->createForm(OptionServiceType::class, $optionService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('option_service_index');
        }

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
        if ($this->isCsrfTokenValid('delete'.$optionService->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($optionService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('option_service_index');
    }
}
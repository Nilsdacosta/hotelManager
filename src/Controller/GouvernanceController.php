<?php

namespace App\Controller;

use App\Entity\AssignationMenage;
use App\Form\GouvernanceType;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GouvernanceController extends AbstractController
{
    /**
     * @Route("/gouvernance", name="gouvernance")
     */
    public function index(Request $request, ChambreRepository $chambreRepository): Response
    {
        $assignation = new AssignationMenage;
        $chambres = $chambreRepository->findAll();
        foreach($chambres as $chambre)
        {
            $form = $this->createForm(GouvernanceType::class, $assignation);

            $formView[] = ['form' => $form->createView(), 'chambre' => $chambre];
        }
        return $this->render('gouvernance/index.html.twig', [
            'formList' => $formView
        ]);
    }

    /**
     * @Route("/form/{id}", name="gouvernance_form_receive")
     */
    public function formReceive(ChambreRepository $chambreRepository, Request $request, $id): Response
    {
            $assignation = new AssignationMenage;
            $chambre = $chambreRepository->find($id);

            $form = $this->createForm(GouvernanceType::class, $assignation);
            $form->handleRequest($request);

            // enregistrement des datas dans la table assignation en fonction de l'id 
            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($assignation);
                $entityManager->flush();
    
                return $this->redirectToRoute('gouvernance');
            }
    }
}

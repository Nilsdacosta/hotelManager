<?php

namespace App\Controller;

use App\Entity\AssignationMenage;
use App\Form\GouvernanceType;
use App\Repository\AssignationMenageRepository;
use App\Repository\ChambreRepository;
use App\Repository\EmployeRepository;
use App\Repository\OptionServiceRepository;
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
            'formList' => $formView,
            'chambres' => $chambres
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
                $assignation->setChambre($chambre);
                $assignation->setdate(new \DateTime);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($assignation);
                $entityManager->flush();
    
                return $this->redirectToRoute('gouvernance');
            }
    }

    /**
     * @Route("gouvernance/historique", name="gouvernance_historique")
     */
    public function show(AssignationMenageRepository $assignationMenageRepository, EmployeRepository $employeRepository, ChambreRepository $chambreRepository, OptionServiceRepository $optionServiceRepository): Response
    {
        # je récupère les informations à afficher pour les filtres et l'historique
        $employes=$employeRepository->findAllGroupeBy('username');
        $chambres = $chambreRepository->findAllGroupeBy('nom');
        $optionResas = $optionServiceRepository->findAllGroupeBy('nomOption');

        # je récupère les données envoyées via le get
        $request = Request::createFromGlobals();
        $dateRequest= $request->query->get('date');
        $employeRequest= $request->query->get('employe');
        $chambreRequest= $request->query->get('chambre');
        $optionRequest= $request->query->get('option');

        if(!empty($request)){
            $assignationMenages = $assignationMenageRepository->historiqueAssignationFiltre($dateRequest, $employeRequest, $chambreRequest,$optionRequest );
        }else{
            $assignationMenages = $assignationMenageRepository->findAll();
        }

        return $this->render('gouvernance/assignationHistorique.html.twig', [
            'assignationMenages' => $assignationMenages,
            'employes'=> $employes,
            'chambres'=> $chambres,
            'optionResas'=>$optionResas
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\OptionService;
use App\Form\GouvernanceType;
use App\Entity\AssignationMenage;
use App\Repository\ChambreRepository;
use App\Repository\EmployeRepository;
<<<<<<< HEAD
use App\Repository\OptionServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
=======
>>>>>>> origin/develop
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AssignationMenageRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GouvernanceController extends AbstractController
{
    // /**
    //  * @Route("/gouvernance", name="gouvernance")
    //  */
    // public function index(Request $request, ChambreRepository $chambreRepository): Response
    // {
    //     $assignation = new AssignationMenage;
    //     $chambres = $chambreRepository->findAll();
    //     foreach($chambres as $chambre)
    //     {
    //         $form = $this->createForm(GouvernanceType::class, $assignation);

    //         $formView[] = ['form' => $form->createView(), 'chambre' => $chambre];
            
    //     }
    //     return $this->render('gouvernance/index.html.twig', [
    //         'formList' => $formView,
    //         'chambres' => $chambres
    //     ]);
    // }

    /**
     * @Route("/gouvernance", name="gouvernance")
     */
    public function index(ChambreRepository $chambreRepository, AssignationMenageRepository $assignationRepository, EmployeRepository $employeRepository): Response
    {
        $employes = $employeRepository->findBy(['poste' => 3]);
        // dump($employes);
        $chambres = $chambreRepository->findAll();
        
        foreach($chambres as $chambre)
        {
            // Je récupère la dernière assignation après validation ou j'instancie un nouvel objet assignation
            $assignation = $assignationRepository->findLastAssignation($chambre->getId());  

            if ($assignation === null) {
                $assignation = new AssignationMenage;
            }

            $form = $this->createForm(GouvernanceType::class, $assignation);

            $formView[] = ['form' => $form->createView(), 'chambre' => $chambre];
        }
        return $this->render('gouvernance/index.html.twig', [
            'formList' => $formView,
            'chambres' => $chambres,
            'employes' => $employes
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

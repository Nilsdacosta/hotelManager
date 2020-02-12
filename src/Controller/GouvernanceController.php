<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Employe;
use App\Entity\OptionService;
use App\Form\GouvernanceType;
use Doctrine\ORM\EntityManager;
use App\Entity\AssignationMenage;

use App\Repository\ChambreRepository;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OptionServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AssignationMenageRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GouvernanceController extends AbstractController
{
    /**
     * @Route("/gouvernance", name="gouvernance")
     */
    public function index(ChambreRepository $chambreRepository, AssignationMenageRepository $assignationRepository, EmployeRepository $employeRepository): Response
    {
        $employes = $employeRepository->findBy(['poste' => 4]);
        // dump($employes);
        $chambres = $chambreRepository->findAll();
        // dump($chambres);
        foreach($chambres as $chambre)
        {
            // Je récupère la dernière assignation après validation ou j'instancie un nouvel objet assignation
            $assignation = $assignationRepository->findLastAssignation($chambre->getId());

            if ($assignation === null || $chambre->getStatutAssignationMenage() == 0) {
                $assignation = new AssignationMenage;
            }

            $form = $this->createForm(GouvernanceType::class, $assignation);

            // Symfony n'autorise pas l'affichage du même formulaire sur la même page donc je les enregistre dans un tableau
            $formView[] = [
                'form' => $form->createView(), 
                'chambre' => $chambre
            ];
            
        }
        return $this->render('gouvernance/index.html.twig', [
            'formList' => $formView,
            'chambres' => $chambres,
            'employes' => $employes,
        ]);
    }

    // Dans cette fonction je termine l'action de la fonction Index avec l'enregistrement de mes données en BDD
    /**
     * @Route("/form/{id}", name="gouvernance_form_receive")
     */
    public function formReceive(ChambreRepository $chambreRepository, OptionServiceRepository $optionServiceRepository, Request $request, $id): Response
    {
            $assignation = new AssignationMenage;
            $chambre = $chambreRepository->find($id);

            $form = $this->createForm(GouvernanceType::class, $assignation);
            $form->handleRequest($request);

            // enregistrement des datas dans la table assignation en fonction de l'id 
            if ($form->isSubmitted() && $form->isValid()) {
                # je check si au moins une option service a été coché, sinon je lui donne une valeur par défault
                if(null !=($assignation->getOptionService())){
                    $option = $optionServiceRepository->findOneBy(['id'=>1]);
                    $assignation->addOptionService($option);
                }
                $assignation->setChambre($chambre);
                $chambre->setStatutAssignationMenage(1);
                $assignation->setdate(new \DateTime);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($chambre);
                $entityManager->persist($assignation);
                $entityManager->flush();

                return $this->redirectToRoute('gouvernance');
            }
    }
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

    /**
     * @Route("gouvernance/zerotage", name="gouvernance_zerotage")
     */
    public function zerotage(ChambreRepository $chambreRepository, EntityManagerInterface $entityManager)
    {
        
        $assignations = $chambreRepository->findAll();
        foreach($assignations as $assignation)
        {
            $assignation->setStatutAssignationMenage(0);
            $entityManager->persist($assignation);
            $entityManager->flush();
        }
        return $this->redirectToRoute('gouvernance');
    }
    /**
     * @Route("gouvernance/zerotage/{id}", name="gouvernance_zerotage_unique")
     */
    public function zerotageUnique(ChambreRepository $chambreRepository, EntityManagerInterface $entityManager, $id, AssignationMenageRepository $assignationMenageRepository)
    {
        $chambre = $chambreRepository->findOneBy(['id' => $id]);
        dump($chambre);
        $assignationMenage = $assignationMenageRepository->findOneBy(['chambre' => $chambre->getId()]);
        dump($assignationMenage);
        $chambre->setStatutAssignationMenage(0);
        
        $entityManager->remove($assignationMenage);
        $entityManager->persist($chambre);
        $entityManager->flush();
    
        return $this->redirectToRoute('gouvernance');
    }

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


    /**
     * @Route("gouvernance/historique", name="gouvernance_historique")
     */
    public function show(AssignationMenageRepository $assignationMenageRepository, EmployeRepository $employeRepository, ChambreRepository $chambreRepository, OptionServiceRepository $optionServiceRepository): Response
    {
        # je récupère les informations à afficher pour les filtres et l'historique
        $employes=$employeRepository->findAllGroupeBy('username');
        $chambres = $chambreRepository->findAllGroupeBy('nom');
        $optionResas = $optionServiceRepository->findAllGroupeBy('nomOption');
        $dateDuJour = new \Datetime;

        # je récupère les données envoyées via le get
        $request = Request::createFromGlobals();
        $dateRequest= $request->query->get('date');
        $employeRequest= $request->query->get('employe');
        $chambreRequest= $request->query->get('chambre');
        $optionRequest= $request->query->get('option');

        if(!empty($request)){
            $assignationMenages = $assignationMenageRepository->historiqueAssignationFiltre($dateRequest, $employeRequest, $chambreRequest,$optionRequest );
        }else{
            $assignationMenages = $assignationMenageRepository->findBy([],['date'=>"DESC"]);
        }

        return $this->render('gouvernance/assignationHistorique.html.twig', [
            'assignationMenages' => $assignationMenages,
            'employes'=> $employes,
            'chambres'=> $chambres,
            'optionResas'=> $optionResas,
            'dateDuJour'=> $dateDuJour
        ]);
    }

    /**
     * @Route("gouvernance/delete/{id}", name="assignation_delete", methods={"DELETE"})
     * //@IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, AssignationMenage $assignationMenage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assignationMenage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($assignationMenage);
            $entityManager->flush();
        }
        return $this->redirectToRoute('gouvernance_historique', [    
        ]);
    }
}
//
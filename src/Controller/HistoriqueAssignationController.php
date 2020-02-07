<?php

namespace App\Controller;

use App\Repository\AssignationMenageRepository;
use App\Repository\ChambreRepository;
use App\Repository\EmployeRepository;
use App\Repository\OptionServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueAssignationController extends AbstractController
{
    /**
     * @Route("/historique/assignation", name="historique_assignation" )
     */
    public function index(EmployeRepository $employeRepository,ChambreRepository $chambreRepository, OptionServiceRepository $optionServiceRepository, AssignationMenageRepository $assignationMenageRepository)
    {
        return $this->render('historique_assignation/index.html.twig', [
            'employe' => $employeRepository->findAll(),
            'chambre' => $chambreRepository->findAll(),
            'optionResa' => $optionServiceRepository->findAll(),
            'assignations'=>$assignationMenageRepository->findAll()

        ]);
    }
}

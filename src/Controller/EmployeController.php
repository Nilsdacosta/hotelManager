<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/employe")
 */
class EmployeController extends AbstractController
{
    /**
     * @Route("/", name="employe_index", methods={"GET"})
     */
    public function index(EmployeRepository $employeRepository): Response
    {
        # je défini les requetes pour l'affichage du formulaire de filtre

        $idEmploye = $employeRepository->findAllGroupeBy('id');
        $posteEmploye = $employeRepository->findAllGroupeBy('poste');
        $roleEmploye = $employeRepository->findRoleGroupeBy('roles');

         # je récupère les données envoyées via le get
         $request = Request::createFromGlobals();
         $idEmployeRequest= $request->query->get('id');
         $usernameEmployeRequest= $request->query->get('username');
         $nomEmployeRequest= $request->query->get('nom');
         $prenomEmployeRequest= $request->query->get('prenom');
         $telephoneEmployeRequest= $request->query->get('telephone');
         $posteEmployeRequest= $request->query->get('poste');
         $roleEmployeRequest= $request->query->get('role');

         if(!empty($request)){
            $employes = $employeRepository->employeFiltre($idEmployeRequest,$usernameEmployeRequest,$nomEmployeRequest,$prenomEmployeRequest ,$telephoneEmployeRequest,$posteEmployeRequest ,$roleEmployeRequest);
        }else{
            $employes = $employeRepository->findAll();

        }


        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
            'idEmploye'=>$idEmploye,
            'posteEmploye'=>$posteEmploye,
            'roleEmploye'=>$roleEmploye
        ]);
    }

    /**
     * @Route("/new", name="employe_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $random = random_int(1, 999);
           
            // Création d'un id unique pour chaque employé.
            $usernameUnique =$form->get('nom')->getData() .$form->get('prenom')->getData() . $random ;
            
            // Je remplace les accents potentiels pour faciliter la connexion
            $username = str_replace(array('é', 'ê', 'ë', 'è'), 'e', $usernameUnique);

            $formRole=$form->get('roles')->getData();
            if ($formRole == 1) {
                $employe->setRoles( [Employe::ROLE_1]);
            }elseif($formRole == 2){
                $employe->setRoles( [Employe::ROLE_2]);
            }else{
                $employe->setRoles( [Employe::ROLE_3]);
            }

            $employe->setUsername(strtolower($username));
          
            // Récupération de l'id unique pour que son mot de passe soit le même et faciliter l'enregistrement des employés
            $employe->setPassword(
                $passwordEncoder->encodePassword(
                    $employe,
                   str_replace (" ", "", strtolower($username) ) 
                )
            );           

            // Enregistrement des données dans la BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/new.html.twig', [
            'employe' => $employe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="employe_show", methods={"GET"})
     */
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="employe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Employe $employe): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/edit.html.twig', [
            'employe' => $employe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="employe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Employe $employe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($employe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('employe_index');
    }
}

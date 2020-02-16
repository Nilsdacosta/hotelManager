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

        /* ************************
        |    AFFICHAGE EMPLOYES   |
        **************************/


        # je défini les requetes pour l'affichage du formulaire de filtre
        $idEmploye = $employeRepository->findAllGroupeBy('id');
        $posteEmploye = $employeRepository->findAllGroupeBy('poste');
        $roleEmploye = $employeRepository->findAllGroupeBy('roles');
        $usernameEmploye = $employeRepository->findAllGroupeBy('username');
        $nomEmploye = $employeRepository->findAllGroupeBy('nom');
        $prenomEmploye = $employeRepository->findAllGroupeBy('prenom');
        $telephoneEmploye = $employeRepository->findAllGroupeBy('telephone');

        # je récupère les données envoyées via le get
        $request = Request::createFromGlobals();
        $idEmployeRequest= $request->query->get('id');
        $usernameEmployeRequest= $request->query->get('username');
        $nomEmployeRequest= $request->query->get('nom');
        $prenomEmployeRequest= $request->query->get('prenom');
        $telephoneEmployeRequest= $request->query->get('telephone');
        $roleEmployeRequest= $request->query->get('role');

        if($request->query->get('poste') == "Directeur"){
           $posteEmployeRequest= 1;
        }elseif($request->query->get('poste')=="Réceptionniste"){
           $posteEmployeRequest= 2;
        }elseif($request->query->get('poste')=="Gouvernante"){
           $posteEmployeRequest= 3;
        }elseif($request->query->get('poste')=="Femme de chambre"){
           $posteEmployeRequest= 4;
        }else{
           $posteEmployeRequest= 5;
        }
        

        # je test si le formulaire filtre renvoie des données, sinon j'affiche tout
        if(!empty($request->query)){
            $employes = $employeRepository->employeFiltre($idEmployeRequest,$usernameEmployeRequest,$nomEmployeRequest,$prenomEmployeRequest ,$telephoneEmployeRequest,$posteEmployeRequest ,$roleEmployeRequest);
        }else{
            $employes = $employeRepository->findAll();
        }

        // Je retourne mes informations au template
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
            'idEmploye'=>$idEmploye,
            'posteEmploye'=>$posteEmploye,
            'roleEmploye'=>$roleEmploye,
            'nomEmploye' => $nomEmploye,
            'prenomEmploye' => $prenomEmploye,
            'usernameEmploye' => $usernameEmploye,
            'telephoneEmploye' => $telephoneEmploye
        ]);
    }

    /**
     * @Route("/new", name="employe_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {

        /* ************************
        |       AJOUT EMPLOYES    |
        **************************/

        # J'instance un nouvel objet de la classe employé
        $employe = new Employe();

        # je crée le formulaire
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        
        # Je vérifie sur le formulaire est soumis et s'il est valid
        if ($form->isSubmitted() && $form->isValid()) {

            $random = random_int(1, 999);
           
            // Création d'un id unique pour chaque employé.
            $usernameUnique =$form->get('nom')->getData() .$form->get('prenom')->getData() . $random ;
            
            // Je remplace les accents potentiels pour faciliter la connexion
            $username = str_replace(" ", "", str_replace(array('é', 'ê', 'ë', 'è'), 'e', $usernameUnique));
            
            # je défini le role de l'employé
            $formRole=$form->get('roles')->getData();
            if ($formRole == 1) {
                $employe->setRoles( [Employe::ROLE_1]);
            }elseif($formRole == 2){
                $employe->setRoles( [Employe::ROLE_2]);
            }else{
                $employe->setRoles( [Employe::ROLE_3]);
            }

            # je défini le user pour mon objet
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

            # je redirige vers la page historique employé
            return $this->redirectToRoute('employe_index');
        }

        // Je retourne mes informations au template
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

        /* *************************
        |  AFFICHAGE D'UN EMPLOYE  |
        ***************************/


        // Je retourne mes informations au template
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="employe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Employe $employe): Response
    {

        /* ************************
        |       EDIT EMPLOYES      |
        **************************/

        # je crée un formulaire
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        # si le formulaire est soumis et valid
        if ($form->isSubmitted() && $form->isValid()) {

            #j'enregistre en BDD
            $this->getDoctrine()->getManager()->flush();

            # je redirige vers l'historique employé
            return $this->redirectToRoute('employe_index');
        }


        // Je retourne mes informations au template
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

        /* ************************
        |   SUPPRESSION EMPLOYES  |
        **************************/

        # je supprime en BDD via csrfToken
        if ($this->isCsrfTokenValid('delete'.$employe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($employe);
            $entityManager->flush();
        }


        // Je retourne mes informations au template
        return $this->redirectToRoute('employe_index');
    }
}

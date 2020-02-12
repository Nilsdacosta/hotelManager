<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Address;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(ClientRepository $clientRepository)
    {

        /* ************************
        | AFFICHAGE DES CHAMBRES  |
        **************************/


        # je récupère les données envoyées via le get
        $request = Request::createFromGlobals();
        $nomClient= $request->query->get('nom');
        $prenomClient= $request->query->get('prenom');
        $adresse= $request->query->get('adresse');
        $codePostal= $request->query->get('codePostal');
        $ville= $request->query->get('ville');
        $telephone= $request->query->get('Telephone');
        $mail= $request->query->get('mail');
        $dateNaissance= $request->query->get('dateNaissance');

        # Affichage des données, je teste si le filtre a été envoyé ou non
        if(!empty($request)){
            $clients=$clientRepository->historiqueClientFiltre($nomClient,$prenomClient, $adresse,$codePostal,$ville,$telephone,$mail,$dateNaissance);
        }else{
            # je cherche tous mes clients et je trie le résultat par nom par ordre croissant
            $clients=$clientRepository->findBy([],['nom'=>'ASC']);
        }

        // Je retourne mes informations au template
        return $this->render('client/index.html.twig', [
            'clients' => $clients,

        ]);
        
    }


    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client): Response
    {

        /* ************************
        |    EDIT DES CHAMBRES    |
        **************************/

        
        # Je crée un formulaire
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        # si le formulaire est soumis et valid j'enregistre en BDD et je redirige vers la page client
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('client');
        }

        // Je retourne mes informations au template
        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }
} 

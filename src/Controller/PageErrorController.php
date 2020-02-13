<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageErrorController extends AbstractController
{
    
    public function showAction()
    {
        # renvoie de la page 404
        return $this->render('page_error/index.html.twig', [
            'controller_name' => 'PageErrorController',
        ]);
    }
}

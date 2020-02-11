<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageErrorController extends AbstractController
{
    
    public function showAction()
    {
        return $this->render('page_error/index.html.twig', [
            'controller_name' => 'PageErrorController',
        ]);
    }
}

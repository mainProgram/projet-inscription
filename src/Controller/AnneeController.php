<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnneeController extends AbstractController
{
    #[Route('/annee', name: 'app_annee')]
    public function index(): Response
    {
        return $this->render('annee/index.html.twig', [
            'controller_name' => 'AnneeController',
        ]);
    }
}

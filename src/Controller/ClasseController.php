<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClasseController extends AbstractController
{
    #[Route('/classe', name: 'app_classe')]
    public function index(ClasseRepository $repo): Response
    {
        return $this->render('classe/index.html.twig', [
            'controller_name' => 'ClasseController', 
            "classes" => $repo->findAll(), 
            "title" => "Liste des classes"
        ]);
    }

    #[Route('/classe/add', name: 'add_classe')]
    public function insert(ClasseRepository $repo): Response
    {
        // $classe = new Classe();
        // $classe->setLibelle("L2 GL");
        // $classe->setFiliere("Génie Logiciel");
        // $classe->setNiveau("Licence 2");
        // $repo->add($classe, true);
        // dd($repo->findAll());
        return $this->render('classe/add.html.twig', [
            "title" => "ajout de classes"
        ]);
    }
}

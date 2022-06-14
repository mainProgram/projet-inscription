<?php

namespace App\Controller;

use App\Entity\Annee;
use App\Form\AnneeType;
use App\Repository\AnneeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnneeController extends AbstractController
{
    #[Route('/annee', name: 'annee-list')]
    public function index(AnneeRepository $repo): Response
    {
        $annees = $repo->findAll();

        return $this->render('annee/index.html.twig', [
            "title" => "Liste des années scolaires",
            "annees" => $annees
        ]);
    }

    #[Route('/annee/{id}/edit', name: 'annee-edit')]
    #[Route('/annee/add', name: 'annee-add')]
    public function add(Request $request, EntityManagerInterface $manager, Annee $annee = null){
        if(! $annee)
            $annee = new Annee();

        $form = $this->createForm(AnneeType::class, $annee);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($annee);
            $manager->flush();

            return $this->redirectToRoute("annee-list");
        }

        return $this->render('annee/add.html.twig', [
            "title" => "Ajout d'année scolaire",
            "form" => $form->createView(),
            "editMode" => $annee->getId() == null
        ]);
    }

}

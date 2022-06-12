<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    #[Route('/classe/add', name: 'classe-add')]
    #[Route('/classe/{id}/edit', name: 'classe-edit')]
    public function add(Request $request, EntityManagerInterface $manager, Classe $classe = null): Response
    {
        if($classe == null)
        {
            $classe = new Classe();
        }
        
        $form = $this->createForm(ClasseType::class, $classe);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($classe);
            $manager->flush();
            return $this->redirectToRoute("classe-list");
        }
        return $this->render('classe/add.html.twig', [
            "title" => "Ajout de classes",
            "form" => $form->createView(),
            'editMode' => $classe->getId() == null
        ]);
    }

    #[Route('/classe', name: 'classe-list')]
    public function listAction(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql   = "SELECT a FROM App\Entity\Classe a WHERE a.etat=1";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        
        // parameters to template
        return $this->render('classe/index.html.twig', [
            'pagination' => $pagination,
            "title" => "Liste des classes"
        ]);
    }

    #[Route('/classe/{id}/delete', name: 'classe-delete')]
    public function delete(Classe $classe, ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();

        if (!$classe) {
            throw $this->createNotFoundException(
                'Classe introuvable !'
            );
        }

        $classe->setEtat(0);
        $entityManager->flush();

        return $this->redirectToRoute("classe-list");
    }

}

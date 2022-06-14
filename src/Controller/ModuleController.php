<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'module-list')]
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $dql   = "SELECT a FROM App\Entity\Module a WHERE a.etat=1";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        // parameters to template
        return $this->render('module/index.html.twig', [
            'title' => 'Liste des Modules',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/module/add', name: 'module-add')]
    #[Route('/module/{id}/edit', name: 'module-edit')]
    public function add(Request $request, EntityManagerInterface $manager, Module $module = null): Response
    {
        if($module == null)
        {
            $module = new Module();
        }
        
        $form = $this->createForm(ModuleType::class, $module);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $module->setRp($user);
            $manager->persist($module);
            $manager->flush();
            return $this->redirectToRoute("module-list");
        }
        return $this->render('module/add.html.twig', [
            "title" => "Ajout de modules",
            "form" => $form->createView(),
            'editMode' => $module->getId() == null
        ]);
    }

    #[Route('/module/{id}/delete', name: 'module-delete')]
    public function delete(Module $module, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        if (!$module) {
            throw $this->createNotFoundException(
                'module introuvable !'
            );
        }

        $module->setEtat(0);
        $entityManager->flush();

       return $this->redirectToRoute("module-list");
    }



}

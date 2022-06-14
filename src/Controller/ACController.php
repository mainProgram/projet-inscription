<?php

namespace App\Controller;

use App\Entity\AC;
use App\Form\RegistrationType;
use App\Repository\ACRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ACController extends AbstractController
{
    // #[Route('/ac', name: 'ac-list')]
    // public function index(ACRepository $repo){
    //     $acs = $repo->findAll();
    //     dd($repo->index());
    //     return $this->render('ac/index.html.twig', [
    //         'pagination' => $acs,
    //         "title" => "Liste des attachés de classes"
    //     ]);
    // }
    #[Route('/ac', name: 'ac-list')]
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $dql   = "SELECT a FROM App\Entity\AC a WHERE a.etat=1";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        
        // parameters to template
        return $this->render('ac/index.html.twig', [
            'pagination' => $pagination,
            "title" => "Liste des attachés de classes"
        ]);
    }


    #[Route('/ac/add', name: 'ac-add')]
    #[Route('/ac/{id}/edit', name: 'ac-edit')]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, AC $user = null): Response{
        
        if($user == null){
            $user = new AC();
            $user->setRoles(["ROLE_AC"]);
        }
        
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $password );;
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("ac-list");
        }

        return $this->render('ac/add.html.twig', [
            "form" => $form->createView(),
            "editMode" => $user->getId() == null,
            "ac" => $user,
            "title" => "Inscription d'un attaché de classe"
        ]);
    }


    #[Route('/ac/{id}/delete', name: 'ac-delete')]
    public function delete(AC $ac, ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();

        if (!$ac) {
            throw $this->createNotFoundException(
                'ac introuvable !'
            );
        }

        $ac->setEtat(0);
        $entityManager->flush();

        return $this->redirectToRoute("ac-list");
    }
}

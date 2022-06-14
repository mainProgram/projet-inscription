<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Module;
use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Form\RegistrationType;
use App\Entity\ProfesseurClasses;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfesseurController extends AbstractController
{
    #[Route('/professeur', name: 'professeur-list')]
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $dql   = "SELECT a FROM App\Entity\Professeur a WHERE a.etat=1";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        
        // parameters to template
        return $this->render('professeur/index.html.twig', [
            'pagination' => $pagination,
            "title" => "Liste des professeurs"
        ]);
    }

    #[Route('/professeur/add', name: 'professeur-add')]
    #[Route('/professeur/{id}/edit', name: 'professeur-edit')]
    public function register(Request $request, EntityManagerInterface $manager, Professeur $user = null): Response{
        if($user == null){
            $user = new Professeur();
        }

        $user->setRp($this->getUser());

        $form = $this->createForm(ProfesseurType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("professeur-details", [
                'id' => $user->getId() 
            ]);
        }

        return $this->render('professeur/add.html.twig', [
            "form" => $form->createView(),
            "editMode" => $user->getId() == null,
        ]);
    }

    #[Route('/professeur/{id}/delete', name: 'professeur-delete')]
    public function delete(Professeur $professeur, ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();

        if (!$professeur) {
            throw $this->createNotFoundException(
                'professeur introuvable !'
            );
        }
        $professeur->setEtat(0);
        $entityManager->flush();

        return $this->redirectToRoute("professeur-list");
    }

    #[Route('/professeur/{id}/details', name: 'professeur-details')]
    public function details(Professeur $professeur = null): Response
    {
        // parameters to template
        return $this->render('professeur/details.html.twig', [
            "title" => "Détails du professeur",
            "professeur" => $professeur
        ]);
    }
    

    #[Route('/professeur/{id}/affecter', name: 'professeur-affecter')]
    public function affecter_classe(Professeur $professeur = null, ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $classes = $doctrine->getRepository(Classe::class)->findByEtat(1);
        $modules = $doctrine->getRepository(Module::class)->findByEtat(1);
        if($request->request->count() > 0)
        {
            foreach($modules as $mod){
                $classesChoisies = $request->request->get("classe_id".$mod->getId());
                if($classesChoisies != null){
                    foreach($classesChoisies as $cla){
                        $c = new ProfesseurClasses();
                        $c->setProfesseur($professeur);
                        $v = $doctrine->getRepository(Classe::class)->find($cla);
                        $c->setClasse($v);
                        $c->setModule($mod);
                        $entityManager->persist($c);
                    }
                }
            }
            $entityManager->flush();
        }

        // parameters to template
        return $this->render('professeur/affecter_classe.html.twig', [
            "title" => "Affectation de classes pour le professeur",
            "modules" => $modules,
            "classes" => $classes,
            "professeur" => $professeur
        ]);
    }
}

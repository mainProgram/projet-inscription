<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'etudiant-list')]
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $dql   = "SELECT a FROM App\Entity\Etudiant a WHERE a.etat=1";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        
        // parameters to template
        return $this->render('etudiant/index.html.twig', [
            'pagination' => $pagination,
            "title" => "Liste des étudiants"
        ]);
    }

    #[Route('/etudiant/add', name: 'etudiant-add')]
    #[Route('/etudiant/{id}/edit', name: 'etudiant-edit')]
    public function register(Request $request, EntityManagerInterface $manager, Etudiant $etudiant = null, AnneeRepository $repo, ClasseRepository $classeRepository, UserPasswordHasherInterface $passwordHasher): Response{
                
        if($etudiant == null){
            $etudiant = new Etudiant();
        }

        $inscription = new Inscription();

        $classes = $classeRepository->findAll();

        // $annee = $repo->findOneBy(array('etat' => 1));
        $annee = $repo->findByEtat(1);

        $acOrRp = $this->getUser();

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //Mettre ds etudiant
            $password = "passer";
            $hashedPassword = $passwordHasher->hashPassword($etudiant, $password );;
            $etudiant->setPassword($hashedPassword);
            $manager->persist($etudiant);
            
            //Inscription etudiant
            $inscription->setAnnee($annee);

            $classe = $classeRepository->find($request->get("classe_id"));
            $inscription->setClasse($classe);
            
            $inscription->setAc($acOrRp);
            $inscription->setEtudiant($etudiant);

            $manager->persist($inscription);
            $manager->flush();
            
            //Update matricule
            if($etudiant->getId() < 100)
                $etu = "00".$etudiant->getId();
            else 
                $etu = "0".$etudiant->getId();

            $matricule = "#ETU_".$etu."_".$etudiant->getPrenom()[0].$etudiant->getNom()[0];
            $etudiant->setMatricule($matricule);
            $etudiant->setRoles(["ROLE_ETUDIANT"]);
            $manager->persist($etudiant);
            $manager->flush();

            return $this->redirectToRoute("etudiant-list");
        }

        return $this->render('etudiant/add.html.twig', [
            "form" => $form->createView(),
            "editMode" => $etudiant->getId() == null,
            "title" => "Inscription d'un étudiant",
            "classes" => $classes
        ]);
    }
}

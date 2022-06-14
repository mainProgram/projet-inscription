<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemandeController extends AbstractController
{
    #[Route('/demande', name: 'app_demande')]
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $dql = "SELECT a FROM App\Entity\Demande a WHERE a.etat=1";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        
        // parameters to template   
        return $this->render('demande/index.html.twig', [
            'pagination' => $pagination,
            "title" => "Liste des demandes"
        ]);
    }

    #[Route('/mesdemandes', name: 'mes_demandes')]
    public function mes_demandes(){
        $user = $this->getUser();
        $demandes = $user->getDemandes();
        return $this->render('demande/index.html.twig', [
            'pagination' => $demandes,
            "title" => "Liste des demandes"
        ]);
    }

    #[Route('/formuler_demande', name: 'formuler_demande')]
    public function formuler_demande(Request $request, EntityManagerInterface $manager){

        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $demande->setDate(new \DateTime());
            $demande->setEtudiant($this->getUser());
            $manager->persist($demande);
            $manager->flush();
            return $this->redirectToRoute("mes_demandes");
        }

        return $this->render('demande/add.html.twig', [
            "title" => "Formulation de demande",
            "form" => $form->createView(),
        ]);
    }

    #[Route('/traiter_demande/{id}', name: 'traiter_demande')]
    public function traiter_demande(Demande $demande, EntityManagerInterface $manager){
        $demande->setTraitement(1);
        $demande->setRp($this->getUser());
        $manager->persist($demande);
        $manager->flush();
        return $this->redirectToRoute("app_demande");
    }

    #[Route('/refuser_demande/{id}', name: 'refuser_demande')]
    public function refuser_demande(Demande $demande,  EntityManagerInterface $manager){
        $demande->setTraitement(2);
        $demande->setRp($this->getUser());
        $manager->persist($demande);
        $manager->flush();
        return $this->redirectToRoute("app_demande");
    }

    
}

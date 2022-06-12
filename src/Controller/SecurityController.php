<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'security_login')]
    public function login(){
        return $this->render('security/index.html.twig', [
            
        ]);
    }

    #[Route('/inscription', name: 'security_register')]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response{
        
        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $password );;
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("security_login");
        }

        return $this->render('security/register.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/deconnexion', name: 'security_logout')]
    public function logout(){}
}

<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * Permet d'inscricre un nouveau utilisateur
     * @Route("/inscription", name="app_register")
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $em): Response
    {
        $user = new Utilisateur();
        
        $user->setIsActif(true);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Attribution du rôle admin
            $isAdmin = $form->get('isAdmin')->getData();
            if ($isAdmin === true) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            // encodage du plainPassword
            $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setUpdatedAt(new DateTime("now"));
            
            $em->persist($user);
            $em->flush();
            
            // Message flash
            $this->addFlash(
                'success', 
                "L'utilisateur {$user->getPrenom()} est crée."
            );
            return $this->redirectToRoute('home');
        
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

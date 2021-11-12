<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {


        $user = new Utilisateur();
        
    
        $user->setIsActif(true);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $u =  $form->get('username')->getData();
          

            
            $isAdmin = $form->get('isAdmin')->getData();
            $isActif = $form->get('isActif')->getData();
            // Attribution du rôle admin

            if ($isAdmin === true) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            // encode the plain password
            $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    

    
    }

}

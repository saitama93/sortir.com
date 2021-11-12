<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="app_profil")
     */
    public function index(Utilisateur $user): Response
    {
        return $this->render('account/profil.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/update/{id}", name="update_profile", methods={"GET","POST"})
     */
    public function update(Request $request, Utilisateur $utilisateur): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/update.html.twig', [
            'registrationForm' => $form->createView(),
            'utilisateur' => $form
        ]);
    }
}

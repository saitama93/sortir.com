<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}

<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function index(): Response
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/sortie/{id}", name="sortie_consultation")
     */
    public function getSortie(Sortie $sortie): Response
    {
        return $this->render('sortie/consultation.html.twig', [
            'sortie' => $sortie
        ]);
    }


     /**
     * @Route("/ajoutSortie", name="sortie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sortie = new Sortie();

       $utilisateur =$this->getUser();
        $sortie->setOrganisateur($utilisateur);
        $sortie->setSite($utilisateur->getSiteRattachement());
    
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }
}

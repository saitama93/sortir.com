<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * Permet à l'utilisateur de faire une réservation
     * 
     * @Route("/reserver/{id}", name="reservation_new")
     *
     * @return void
     */
    public function reservation(Sortie $sortie, EntityManagerInterface $em){
        $user = $this->getUser();
        $verif = null;
        // dd($user);
        $nbParticipants = count($sortie->getParticipants());
        // dd($nbParticipants);

        // On vérifie si l'utilisateur participe déjà la sortie
        foreach ($sortie->getParticipants() as $participant) {
            if ($participant == $user) {
                $verif = true;
            }else{
                $verif = false;
            }
        }

        //On vérifie  si il rest une place de libre pour cette sortie
        if ($nbParticipants < $sortie->getNbInscriptionsMax()) {
            $sortie->addParticipant($user);
            $em->persist($sortie);
            $em->flush();

            $this->addFlash(
                'success',
                "Votre réservation à bien été prise en compte"
            );
            return $this->redirectToRoute('home');
        }else{
            $this->addFlash(
                'danger',
                "Il n'y a plus de place pour de disponible pour cette sortie" 
            );
            return $this->redirectToRoute('home');
        }
    }
}

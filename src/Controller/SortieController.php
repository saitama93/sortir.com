<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\Utilisateur;
use DateTime;
use DateInterval;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CancelSortieFormType;

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
    public function getSortie(Sortie $sortie, SortieRepository $repo): Response
    {
        return $this->render('sortie/consultation.html.twig', [
            'sortie' => $sortie
        ]);
    }


    /**
     * @Route("/ajoutSortie", name="sortie_new", methods={"GET","POST"})
     */
    public function new(Request $request, EtatRepository $etatRepository): Response
    {
        $sortie = new Sortie();

        $utilisateur = $this->getUser();
        $sortie->setOrganisateur($utilisateur);
        $sortie->setSite($utilisateur->getSiteRattachement());
        $sortie->setEtat($etatRepository->find(1));

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
     * @Route("modifierSortie/{id}", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {

        $utilisateur = $this->getUser();
        $organisateur = $sortie->getOrganisateur();

        if ($utilisateur != $organisateur) {
            $this->addFlash(
                'danger',
                "Impossible de modifier une sortie dont vous n'êtes pas l'organisateur."
            );
            return $this->redirectToRoute('home');
        } else {



            $form = $this->createForm(SortieType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('sortie/edit.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
            ]);
        }
    }

    /**
     * Permet à l'utilisateur de faire une réservation
     * @Route("/reserver/{id}", name="reservation_new")
     * @param Sortie $sortie
     * @param EntityManagerInterface $em
     * @return void
     */
    public function reservation(Sortie $sortie, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $verif = null;
        $nbParticipants = count($sortie->getParticipants());

        // On vérifie si l'utilisateur participe déjà la sortie
        foreach ($sortie->getParticipants() as $participant) {
            if ($participant == $user) {
                $verif = true;
            } else {
                $verif = false;
            }
        }

        //On vérifie  si il rest une place de libre pour cette sortie
        if ($nbParticipants < $sortie->getNbInscriptionsMax()) {
            if ($sortie->getDateLimiteInscription() < new DateTime("now")) {
                $this->addFlash(
                    'danger',
                    "Vous avez dépassé la date limite d'inscription pour cette sortie."
                );
                return $this->redirectToRoute('home');
            } else {
                $sortie->addParticipant($user);
                $em->persist($sortie);
                $em->flush();

                $this->addFlash(
                    'success',
                    "Votre réservation a bien été prise en compte."
                );
                return $this->redirectToRoute('home');
            }
        } else {
            $this->addFlash(
                'danger',
                "Il n'y a plus de place disponible pour cette sortie."
            );
            return $this->redirectToRoute('home');
        }
    }

    /**
     * Permet de se désinscrire d'une sortie
     * @Route("/désinscrire/{id}", name="desinscription")
     *
     * @param Sortie $sortie
     * @param EntityManagerInterface $em
     * @return void
     */
    public function desinscription(Sortie $sortie, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        if ($sortie->getParticipants()->contains($user)) {
            $sortie->removeParticipant($user);
            $this->addFlash(
                'success',
                "Vous êtes désinscrit de la sortie."
            );

            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('home');
        } else {
            $this->addFlash(
                'danger',
                "Vous n'êtes pas inscrit à cette sortie."
            );
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("annulerSortie/{id}", name="sortie_annuler", methods={"GET","POST"})
     */
    public function annulerSortie(Request $request, Sortie $sortie, EtatRepository $etatRepo): Response
    {
        $user = $this->getUser();
        $today =  new DateTime('NOW');
        $today = $today->add(new DateInterval('PT1H'));
        // On vérifie si la personne connectée est l'organisateur ou si elle est admin

        if (($user == $sortie->getOrganisateur() || $user->getIsAdmin() )&& $sortie->getDateLimiteInscription() > $today ) {

            $form = $this->createForm(CancelSortieFormType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $sortie->setEtat($etatRepo->find(6));
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    "Sortie bien annulée"
                );

                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('sortie/cancel.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
            ]);
        } else {

            if($sortie->getDateLimiteInscription() <= $today){
                $this->addFlash(
                    'danger',
                    "Impossible d'annuler une sortie où la date d'inscription est terminée"
                ); 
            }

            else{
                $this->addFlash(
                    'danger',
                    "Impossible pour vous d'annuler cette sortie"
                );
            }
            
            return $this->redirect($request->headers->get('referer'));
        }
    }

    /**
     * @Route("/publier/{id}", name="publier")
     *
     * @param Sortie $sortie sortie à publier
     * @param EtatRepository $etatRepository etatRepository
     * @param EntityManagerInterface $em
     * @return void
     */
    public function publier(Sortie $sortie, EtatRepository $etatRepository, EntityManagerInterface $em)
    {
        $sortie->setEtat($etatRepository->find(2));
        $em->persist($sortie);
        $em->flush();
        $this->addFlash(
            'success',
            "La sortie a été publiée."
        );
        return $this->redirectToRoute('sortie_consultation', array('id' => $sortie->getId()));
    }
}

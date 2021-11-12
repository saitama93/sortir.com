<?php

namespace App\Controller;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SortieRepository $repo): Response
    {
        $sorties = $repo->findAll();



        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
        ]);
    }


    
}

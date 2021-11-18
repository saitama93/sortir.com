<?php

namespace App\Controller;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Data\SearchData;
use App\Form\SearchForm;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SortieRepository $repo, Request $request): Response
    {

        
        $data = new SearchData();
   
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $sorties = $repo->findSearch($data, $this->getUser());

       return $this->render('sortie/index.html.twig', [
        'sorties' => $sorties,
        'form' => $form->createView(),
    ]);
       
        }

       
}

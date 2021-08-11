<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\FilterActivityType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(SortieRepository $sortieRepository): Response
    {
        $FilterActivityForm = $this->createForm(FilterActivityType::class);

        //todo: Traiter le formulaire du filtre page d'acceuil

        $sorties = $sortieRepository->findSorties();


        return $this->render('main/home.html.twig', [
            'FilterActivityForm' => $FilterActivityForm->createView(),
            'sorties' => $sorties
            ]);
    }
}

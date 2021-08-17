<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\CreateSortieType;
use App\Form\FilterActivityType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();

        // $filtre = new Filtre()
        $filterActivityForm = $this->createForm(FilterActivityType::class);
       // $filterActivityForm = $this->createForm(FilterActivityType::class, $filtre);

        $filterActivityForm->handleRequest($request);

        //Traiter le formulaire du filtre page d'acceuil
        if ($filterActivityForm->isSubmitted()) {
            $campus = $filterActivityForm['Campus']->getData()->getId();
            $motCle = $filterActivityForm['Search']->getData();
            $dateDebut = $filterActivityForm['DateStart']->getData();
            $dateLimitInscription = $filterActivityForm['DateEnd']->getData();
            $checkBoxs = $filterActivityForm['Filter']->getData();





            $sorties = $sortieRepository->findSorties($campus, $motCle, $dateDebut, $dateLimitInscription, $checkBoxs, $user);



            return $this->render('main/home.html.twig', [
                'FilterActivityForm' => $filterActivityForm->createView(),
                'sorties' => $sorties,

            ]);
        }
        $sorties = $sortieRepository->findAll();
        //$sorties = $sortieRepository->findSorties($filtre, $user);


        return $this->render('main/home.html.twig', [
            'FilterActivityForm' => $filterActivityForm->createView(),
            'sorties' => $sorties,

            ]);

    }

    /**
     * @Route ("/create", name="main_create")
     */
    public function create(UtilisateurRepository $utilisateurRepository, Request $request, LieuRepository $lieuRepository):Response
    {

        $sortie = new Sortie();
        $user = $utilisateurRepository->find($this->getUser()->getId());
        $sortie->setCampus($user->getCampus());
        $lieux = $lieuRepository->findAll();

        $createSortieForm = $this->createForm(CreateSortieType::class);
        $createSortieForm->handleRequest($request);

        return $this->render('main/create.html.twig',[
            'createSortieForm' => $createSortieForm->createView(),
            'lieux'=> $lieux,
        ]);
    }


}

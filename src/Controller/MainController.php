<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\FilterActivityType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();
        $filterActivityForm = $this->createForm(FilterActivityType::class);

        $filterActivityForm->handleRequest($request);

        //todo: Traiter le formulaire du filtre page d'acceuil
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


        return $this->render('main/home.html.twig', [
            'FilterActivityForm' => $filterActivityForm->createView(),
            'sorties' => $sorties,

            ]);

    }


}

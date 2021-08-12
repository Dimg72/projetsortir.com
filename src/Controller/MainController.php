<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\FilterActivityType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(SortieRepository $sortieRepository): Response
    {
        $user = $this->getUser();
        $FilterActivityForm = $this->createForm(FilterActivityType::class);

        //todo: Traiter le formulaire du filtre page d'acceuil

        $sorties = $sortieRepository->findSorties();

        //verification inscription

        $verifInsciption=array();

        $i=0;
        foreach($sorties as $sortie){
          $tableauParticipants = $sortie->getParticipants() ;
          $verifInsciption[$i] = false;
          foreach ($tableauParticipants as $participant){
              if($participant->getId() == $user->getId())
              {
                  $verifInsciption[$i] = true;

              }

          }
          $i++;
        }

        return $this->render('main/home.html.twig', [
            'FilterActivityForm' => $FilterActivityForm->createView(),
            'sorties' => $sorties,
            'verifInsciption'=>$verifInsciption
            ]);
    }
}

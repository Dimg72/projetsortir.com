<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/participants/details/{id}", name="participants_details")
     */
    public function details(Participant $user): Response
    {
        return $this->render('utilisateur/detailsParticipant.html.twig',[
            "utilisateur" => $user
        ]);
    }

    /**
     * @Route("/participants", name="participants_liste")
     */
    public function liste(UtilisateurRepository $utilisateurRepository): Response
    {
        $users = $utilisateurRepository->findAll();

        return $this->render('utilisateur/listeParticipants.html.twig', [
            "utilisateurs" => $users
        ]);
    }
}

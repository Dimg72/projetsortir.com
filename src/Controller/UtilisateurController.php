<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateurs/details/{id}", name="utilisateurs_details")
     */
    public function details(Utilisateur $user): Response
    {
        return $this->render('utilisateur/detailsParticipant.html.twig',[
            "utilisateur" => $user
        ]);
    }

    /**
     * @Route("/utilisateurs/", name="utilisateurs")
     */
    public function liste(UtilisateurRepository $utilisateurRepository): Response
    {
        $users = $utilisateurRepository->findAll();

        return $this->render('utilisateur/listeUtilisateurs.html.twig', [
            "utilisateurs" => $users
        ]);
    }
}

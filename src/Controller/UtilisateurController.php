<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Form\UpdateParticipantProfileType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function Sodium\add;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/participants/details/{id}", name="participants_details")
     */
    public function details($id, UserPasswordEncoderInterface $passwordEncoder, Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $participant = $utilisateurRepository->findOneBySomeField($id);
        $idUserAuth = $this->getUser()->getId();
        if($idUserAuth === $participant->getId()) {
            $form = $this->createForm(UpdateParticipantProfileType::class, $participant);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                // encode the plain password
                if ($form->get('plainPassword')->getData())
                    {
                        $participant->setPassword(
                            $passwordEncoder->encodePassword(
                                $participant,
                                $form->get('plainPassword')->getData()
                            )
                        );
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($participant);
                    $entityManager->flush();

                return $this->redirectToRoute('main_home');
            }

            return $this->render('utilisateur/updateProfile.html.twig', [
                "utilisateur" => $participant,
                "updateForm" => $form->createView(),
            ]);


        }else {
            return $this->render('utilisateur/detailsParticipant.html.twig', [
                'participant' => $participant,
            ]);
        }

    }

    /**
     * @Route("/participants", name="participants_liste")
     */
    public function liste(UtilisateurRepository $utilisateurRepository): Response
    {
        $participants = $utilisateurRepository->findAll();

        return $this->render('utilisateur/listeParticipants.html.twig', [
            "utilisateurs" => $participants
        ]);
    }
}

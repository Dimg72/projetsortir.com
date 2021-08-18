<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\ProfilePhoto;
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
            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                if ($form->get('plainPassword')->getData()) {
                    $participant->setPassword(
                        $passwordEncoder->encodePassword(
                            $participant,
                            $form->get('plainPassword')->getData()
                        )
                    );
                }

                //Vérification de l'entrés ou non d'une nouvelle image
                if ($form->get('profilePhoto')->getData()) {
                    //suppression de la photo existant si elle existe
                    if ($participant->getProfilePhoto())
                    {
                        $imageActuelle = $participant->getProfilePhoto();
                        //on supprime le fichier du dossier
                        unlink($this->getParameter('images_directory').'/'.$imageActuelle->getPhotoProfileTag());

                        //on supprime les informations de l'image de la base
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($imageActuelle);
                        $entityManager->flush();

                    }

                    //Récupération de l'image
                    $image = $form->get('profilePhoto')->getData();
                    //on génére un nouveau nom de fichier
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    //on copie le fichier dans le dossier des photos de profil
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    //On stock le nom de l'image dans la base de données

                    $img = new ProfilePhoto();
                    $img->setPhotoProfileTag($fichier);
                    $participant->setProfilePhoto($img);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($participant);
                $entityManager->flush();

                $this->addFlash('success', 'Profil mis à jour');

                return $this->redirectToRoute('main_home');
            }

            return $this->render('utilisateur/updateProfile.html.twig', [
                "utilisateur" => $participant,
                "updateForm" => $form->createView(),
            ]);


        }
        else
            {
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
        $participants = $utilisateurRepository->findAllAsc();


        return $this->render('utilisateur/listeParticipants.html.twig', [
            "participants" => $participants
        ]);
    }

    /**
     * @Route ("/participants/delete/img//{id}/{name}", name="participants_delete_img")
     */
    public function deleteImgProfile(Participant $participant,String $name){
        if ($participant->getId() === $this->getUser()->getId())
        {
            unlink($this->getParameter('images_directory').'/'.$name);

            $em = $this->getDoctrine()->getManager();
            $em->remove($participant->getProfilePhoto());
            $em->flush();

            $this->addFlash('primary', 'La photo de profil a bien été supprimée');
        }

        return $this->redirectToRoute('participants_details', ['id' => $participant->getId()]);
    }
}

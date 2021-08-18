<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\ProfilePhoto;
use App\Entity\Sortie;
use App\Form\RegistrationFormType;
use App\Form\SuppUtilisateursType;
use App\Repository\SortieRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/admin/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Participant();
        $user->setActif(true);
        $user->setAdministrateur(false);
        $user->setRoles(["ROLE_USER"]);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            //Récupération de l'image s'il y en a une
            if ($form->get('profilePhoto')->getData())
                {
                    $image = $form->get('profilePhoto')->getData();
                    dd($form->get('profilePhoto')->getData());
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
                    $user->setProfilePhoto($img);
                }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le profil pour '.$user->getEmail().' a bien été enregistré');

            return $this->redirectToRoute('main_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/suppression", name="app_suppression")
     */
    public function suppreUtili(Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository) : Response {


        $supUtilForm = $this->createForm(SuppUtilisateursType::class);
        $supUtilForm->handleRequest($request);

        if($supUtilForm->isSubmitted() AND $supUtilForm->isValid()){
            $participants = $supUtilForm['email']->getData();

            foreach($participants as $unParticipant) {
                $sorties = $sortieRepository->findOrganisateurId($unParticipant->getId());
                foreach ($sorties as $uneSortie){
                    $entityManager->remove($uneSortie);
                }
                $entityManager->remove($unParticipant);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Le(s) profil(s) ont bien été supprimé(s)');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('utilisateur/suppressionUtilisateur.html.twig', [
             'supUtilForm' =>$supUtilForm->createView()
        ]);
    }


}

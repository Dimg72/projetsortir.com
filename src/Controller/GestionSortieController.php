<?php

namespace App\Controller;



use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;


class GestionSortieController extends AbstractController
{
    /**
     * @Route("/gestion/sortie", name="gestion_sortie")
     */
    public function index(): Response
    {
        return $this->render('gestion_sortie/detailsParticipant.html.twig', [
            'controller_name' => 'GestionSortieController',
        ]);
    }

    /**
     * @Route("/gestion/annulationsortie/{id}", name="gestion_sortie/annuler")
     */
    public function annulerSortie(Sortie $sortieC, Request $request, EntityManagerInterface $entityManager,
                                  SortieRepository $sortieRepository, EtatRepository $etatRepository) : Response {

        // Formulaire pour l'envoi du motif en cas d'annulation
         $sortieCForm = $this->createForm(AnnulerSortieType::class, $sortieC);
         $sortieCForm->handleRequest($request);

         // requête sql récupération des valeurs en fonction de l'id de la sortie et de l'utilisateur.
        // Pour afficher les informations sur la page.
         $sortieId = $sortieRepository->findIdAnnulSortie($sortieC->getId());


         // Permet de récuperer l'id 6 : état annulée
         $etat = new Etat();
         // Etat 6 : Annulée
         $etat = $etatRepository->find(12);

         $dateDuJour = new \DateTime();

            // si la date du jour est inférieur à celle de la date de début de la sortie
         if($dateDuJour <= $sortieC->getDateHeureDebut()) {
             if($sortieCForm->isSubmitted() && $sortieCForm->isValid()) {

                 // Changement à l'état annulée
                 $sortieC->setEtat($etat);


                 $entityManager->persist($sortieC);
                 $entityManager->flush();


                 $this->addFlash('sucess', 'Annulation de votre sortie, validée');
                 return $this->redirectToRoute('main_home');
             }

         }
         // Si date de début de sortie est dépassé, envoie de message d'erreur d'annulation
//         elseif($dateDuJour >= $sortieC->getDateHeureDebut()) {
//             $this->addFlash('fail', 'Vous avez passé la date limite pour pouvoir annuler');
//             return $this->redirectToRoute('main_home');
//         }


            return $this->render('gestion_sortie/sortieannulee.html.twig', [ 'sorties' => $sortieId,
                'sortieCancelForm' => $sortieCForm->createView()
        ]);
    }


    /**
     * @Route("/gestion/inscriresortie/{id}", name="gestion_sortie/sinscrire")
     */
    public function inscrireSortie(Sortie $sortie, EtatRepository $etatRepository,
                                   EntityManagerInterface $entityManager, SortieRepository $sortieRepository) : Response {


            $dateDuJour = new \DateTime();
            //  informations de l'utilisateur.
            $participant = $this->getUser();

            // Permet de récuperer l'id 2 : état Ouverte
            $etat = new Etat();
            $etat = $etatRepository->find(8)->getLibelle();


            // Si la date d'inscription est supérieur à la date du jours et que l'état de la sortie est "ouverte"
       if($sortie->getDateLimiteInscription() > $dateDuJour AND $sortie->getEtat()->getLibelle() === $etat) {


                // inscription du participant dans la sortie
                $sortie->addParticipant($participant);
                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('sucess', 'Inscription à la sortie, validée');
                return $this->redirectToRoute('main_home');
            }
       else {
                $this->addFlash('fail', 'Inscription à la sortie non valide : date ou état non valide');
                return $this->redirectToRoute('main_home');
       }
    }


    /**
     * @Route("/gestion/desistersortie/{id}", name="gestion_sortie/desister")
     */
    public function desisterSortie(Sortie $sortie, EntityManagerInterface $entityManager) : Response {
        $dateDuJour = new \DateTime();

        // lecture des informations de l'utilisateur
        $partipant = $this->getUser();


        if($sortie->getDateHeureDebut() > $dateDuJour) {

            // suppression de l'inscription de l'utilisateur à la sortie
            $sortie->removeParticipant($partipant);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('sucess', 'Desincription à la sortie, validée');
            return $this->redirectToRoute('main_home');
        }
        else{
            $this->addFlash('fail', 'Desincription à la sortie non valide, date passée');
            return $this->redirectToRoute('main_home');
        }
    }




}

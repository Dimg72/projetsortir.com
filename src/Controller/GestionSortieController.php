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


         $sortieCForm = $this->createForm(AnnulerSortieType::class, $sortieC);

         $sortieCForm->handleRequest($request);

         $sortieId = $sortieRepository->findIdAnnulSortie($sortieC->getId());


         // Permet de récuperer l'id 6 : état annulée
         $etat = new Etat();
         $etat = $etatRepository->find(6);

         $dateDuJour = new \DateTime();;


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
         elseif($dateDuJour >= $sortieC->getDateHeureDebut()) {
             $this->addFlash('fail', 'Vous avez passé la date limite pour pouvoir annuler');
             return $this->redirectToRoute('main_home');
         }


            return $this->render('gestion_sortie/sortieannulee.html.twig', [ 'sorties' => $sortieId,
                'sortieCancelForm' => $sortieCForm->createView()


        ]);
    }


    /**
     * @Route("/gestion/inscriresortie/{id}", name="gestion_sortie/inscrire")
     */
    public function inscrireSortie($id, Sortie $sortie, EtatRepository $etatRepository,
                                   EntityManagerInterface $entityManager, SortieRepository $sortieRepository) : Response {


        $dateDuJour = new \DateTime();
            $participant = $this->getUser();

           // $sortie->getId($id);

            // Permet de récuperer l'id 2 : état Ouverte
            $etat = new Etat();
            $etat = $etatRepository->find(2);

       if($sortie->getDateLimiteInscription() < $dateDuJour AND $sortie->getEtat()->getId() === $etat->getId() ) {

                // inscription du participant dans la sortie
                $sortie->addParticipant($participant);
                dd($sortie);
                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->render('main/home.html.twig');
            }



        }


    /**
     * @Route("/gestion/desistersortie/{id}", name="gestion_sortie/desister")
     */
    public function desisterSortie(Sortie $sortie, EntityManagerInterface $entityManager) : Response {
        $dateDuJour = new \DateTime();

        $partipant = $this->getUser();


        if($sortie->getDateHeureDebut() < $dateDuJour) {
            $sortie->removeParticipant($partipant);
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->render('main/home.html.twig');
        }
    }


}

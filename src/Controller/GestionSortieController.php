<?php

namespace App\Controller;


use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
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
    public function annulerSortie(Sortie $sortieC, Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository) : Response {


        $sortieCForm = $this->createForm(AnnulerSortieType::class, $sortieC);

         $sortieCForm->handleRequest($request);

         $sortieId = $sortieRepository->findIdAnnulSortie($sortieC->getId());


          $dateDuJour = new Date();
         if($dateDuJour <= $sortieC->getDateHeureDebut()) {
             if($sortieCForm->isSubmitted() && $sortieCForm->isValid()) {


                 $entityManager->persist($sortieC);
                 $entityManager->flush();

                 $this->addFlash('sucess', 'Annulation de votre sortie, validée');
                 return $this->redirectToRoute('main_home');
             }

         }

            return $this->render('gestion_sortie/sortieannulee.html.twig', [ 'sorties' => $sortieId,
                'sortieCancelForm' => $sortieCForm->createView()


        ]);
        //todo: Faire if pour annuler la sortie une fois enregistré
    }


    /**
     * @Route("/gestion/inscrireSortie", name="gestion_sortie/inscrire")
     */
    public function inscrireSortie(Participant $participant, EntityManagerInterface $entityManager) {

    }


}

<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AnnulerSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
    public function annulerSortie(Request $request, EntityManagerInterface $entityManager, Sortie $sortie, Campus $campus,
    Lieu $lieu, Ville $ville) : Response {

        $sortieC = new Sortie();
        $sortieCForm = $this->createForm(AnnulerSortieType::class, $sortieC);
        $sortieCForm->handleRequest($request);
        dump($sortieC);

        if($sortieCForm->isSubmitted() && $sortieCForm->isValid()) {


            $entityManager->persist($sortieC);
            $entityManager->flush();

            $this->addFlash('sucess', 'Annulation de votre sortie, validée');
            return $this->redirectToRoute('main_home');
        }

            return $this->render('gestion_sortie/sortieannulee.html.twig', [ 'sortie' => $sortie, 'campus' => $campus,
                'lieu' => $lieu, 'ville' => $ville,
                'sortieCancelForm' => $sortieCForm->createView()
        ]);
        //todo: Faire if pour annuler la sortie une fois enregistré
    }








    /**
     * @Route("/gestion/inscrireSortie", name="gestion_sortie/inscrire")
     */
    public function inscrireSortie() {

    }


}

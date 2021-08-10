<?php

namespace App\Controller;

use App\Entity\Sortie;
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
        return $this->render('gestion_sortie/index.html.twig', [
            'controller_name' => 'GestionSortieController',
        ]);
    }

    /**
     * @Route("/gestion/annulationsortie", name="gestion_sortie/annuler")
     */
    public function annulerSortie(Request $request, EntityManagerInterface $entityManager) : Response {

        $sortieC = new Sortie();
        $sortieCForm = $this->createForm(AnnulerSortieType::class, $sortieC);
        $sortieCForm->handleRequest($request);

        if($sortieCForm->isSubmitted() && $sortieCForm->isValid()) {
            
            $sortieC->setMotif($sortieC);
            $entityManager->persist($sortieC);
            $entityManager->flush();

            $this->addFlash('sucess', 'Annulation de votre sortie, validée');
            return $this->redirectToRoute('main_home');
        }

            return $this->render('gestion_sortie/sortieannulee.html.twig', [
                'sortieCancelForm' => $sortieCForm->createView()
        ]);

    }

}

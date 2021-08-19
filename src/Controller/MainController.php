<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\CreateSortieType;
use App\Form\FilterActivityType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();

        // $filtre = new Filtre()
        $filterActivityForm = $this->createForm(FilterActivityType::class);
       // $filterActivityForm = $this->createForm(FilterActivityType::class, $filtre);

        $filterActivityForm->handleRequest($request);

        //Traiter le formulaire du filtre page d'acceuil
        if ($filterActivityForm->isSubmitted()) {
            $campus = $filterActivityForm['Campus']->getData()->getId();
            $motCle = $filterActivityForm['Search']->getData();
            $dateDebut = $filterActivityForm['DateStart']->getData();
            $dateLimitInscription = $filterActivityForm['DateEnd']->getData();
            $checkBoxs = $filterActivityForm['Filter']->getData();





            $sorties = $sortieRepository->findSorties($campus, $motCle, $dateDebut, $dateLimitInscription, $checkBoxs, $user);



            return $this->render('main/home.html.twig', [
                'FilterActivityForm' => $filterActivityForm->createView(),
                'sorties' => $sorties,

            ]);
        }
        $campus = $user->getCampus()->getId();

        $sorties = $sortieRepository->findSortieByCampus($campus);
        //$sorties = $sortieRepository->findSorties($filtre, $user);


        return $this->render('main/home.html.twig', [
            'FilterActivityForm' => $filterActivityForm->createView(),
            'sorties' => $sorties,

            ]);

    }

    /**
     * @Route ("/create", name="main_create")
     */
    public function create(UtilisateurRepository $utilisateurRepository, EtatRepository $etatRepository, Request $request, LieuRepository $lieuRepository, EntityManagerInterface $entityManager):Response
    {

        $sortie = new Sortie();
        $lieux = $lieuRepository->findAll();

        $createSortieForm = $this->createForm(CreateSortieType::class, $sortie);

        $createSortieForm->handleRequest($request);

        if ($createSortieForm->isSubmitted())
        {

            $sortie->setOrganisateur($this->getUser());
            $sortie->setCampus($this->getUser()->getCampus());
            $nomLieu = $request->request->get("lieuRecup");
            $lieuRecup = $lieuRepository->FindLieuWithName($nomLieu);
            $sortie->setLieu($lieuRecup[0]);
            $valueEtat = $request->request->get("Enregistrer");
            $valueEtat2 = $request->request->get("Publier");
            $etat=null;
            if($valueEtat == 1)
            {
                $etat = $etatRepository->find(1);
            }
            if($valueEtat2 == 2)
            {
                $etat = $etatRepository->find(2);
            }

            $sortie->setEtat($etat);


            $entityManager->persist($sortie);

            $entityManager->flush();

            $this->addFlash('success', 'Votre sortie a bien été ajouté!');
            return $this->redirectToRoute('main_home');
       }

        return $this->render('main/create.html.twig',[
            'createSortieForm' => $createSortieForm->createView(),
            'lieux'=> $lieux,
        ]);
    }

    /**
     * @Route ("/sortie/{id}", name="sortie_detail")
     */
    public function detailSortie(int $id, SortieRepository $sortieRepository)
    {
        $sortie = $sortieRepository->findOneBySomeField($id);

        return $this->render('main/detailsSortie.html.twig', [
            'sortie' => $sortie
        ]);
    }
}

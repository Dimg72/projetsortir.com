<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/campus")
 */
class CampusController extends AbstractController
{
    /**
     * @Route("/", name="campus_index", methods={"GET"})
     */
    public function index(CampusRepository $campusRepository): Response
    {
        return $this->render('campus/index.html.twig', [
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="campus_new", methods={"GET","POST"})
     */
    public function new(Request $request, CampusRepository $campusRepository): Response
    {
        $campus = new Campus();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$campusRepository->findOneBySomeField($form->get('nom')->getData()))
                {

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($campus);
                    $entityManager->flush();

                    $this->addFlash('success', 'Le campus a bien été ajouté');

                    return $this->redirectToRoute('campus_index', [], Response::HTTP_SEE_OTHER);
                }
            else
                {
                    $this->addFlash('fail', 'Le campus existe déjà');
                    return $this->render('campus/new.html.twig', [
                        'campus' => $campus,
                        'form' => $form->createView(),
                    ]);
                }
        }

        return $this->render('campus/new.html.twig', [
            'campus' => $campus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campus_show", methods={"GET"})
     */
    public function show(Campus $campus): Response
    {
        return $this->render('campus/show.html.twig', [
            'campus' => $campus,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="campus_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Campus $campus, CampusRepository $campusRepository): Response
    {
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$campusRepository->findOneBySomeField($form->get('nom')->getData()))
                {
                    $this->getDoctrine()->getManager()->flush();

                    $this->addFlash('success', 'Le campus a bien été mis à jours');

                    return $this->redirectToRoute('campus_index', [], Response::HTTP_SEE_OTHER);
                }
            else
                {
                    $this->addFlash('fail', 'ce campus existes déjà');

                    return $this->render('campus/edit.html.twig', [
                        'campus' => $campus,
                        'form' => $form->createView(),
                    ]);
                }
        }

        return $this->render('campus/edit.html.twig', [
            'campus' => $campus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campus_delete", methods={"POST"})
     */
    public function delete(Request $request, Campus $campus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($campus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('campus_index', [], Response::HTTP_SEE_OTHER);
    }
}

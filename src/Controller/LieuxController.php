<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lieux')]
class LieuxController extends AbstractController
{
    #[Route('/lieux', name: 'app_lieux')]
    public function index(Request $request, LieuRepository $lieuRepository, EntityManagerInterface $em): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class);
        $form->setData($lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();
            $em->persist($lieu);
            $em->flush();

            return $this->redirectToRoute('app_lieux');
        }

        return $this->render('lieux/index.html.twig', [
            'lieux' => $lieuRepository->findAll(),
            'form_lieu' => $form->createView(),
        ]);
    }

    #[Route('/lieux/{id}/edit', name: 'app_lieux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();

            return $this->redirectToRoute('app_lieux');
        }

        return $this->render('lieux/edit.html.twig', [
            'form_lieu' => $form->createView(),
        ]);
    }

    #[Route('/lieux/{id}/delete', name: 'app_lieux_delete', methods: ['POST'])]
    public function delete(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $lieu->getId(), $request->request->get('_csrf_token'))) {
            $entityManager->remove($lieu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lieux');
    }
}

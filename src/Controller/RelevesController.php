<?php

namespace App\Controller;

use App\Entity\Releve;
use App\Form\ReleveType;
use App\Repository\ReleveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RelevesController extends AbstractController
{
    #[Route('/', name: 'app_releves')]
    public function index(Request $request, ReleveRepository $repository, EntityManagerInterface $em): Response
    {
        $releve = new Releve();
        $form = $this->createForm(ReleveType::class);
        $form->setData($releve);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $releve = $form->getData();
            $em->persist($releve);
            $em->flush();

            return $this->redirectToRoute('app_releves');
        }

        return $this->render('releves/index.html.twig', [
            'releves' => $repository->findAll(),
            'form_releve' => $form->createView(),
        ]);
    }

    #[Route('/releves/{id}/edit', name: 'app_releves_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Releve $releve, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReleveType::class, $releve);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($releve);
            $em->flush();

            return $this->redirectToRoute('app_releves');
        }

        return $this->render('releves/edit.html.twig', [
            'form_releve' => $form->createView(),
        ]);
    }

    #[Route('/releves/{id}/delete', name: 'app_releves_delete', methods: ['POST'])]
    public function delete(Request $request, Releve $releve, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('delete' . $releve->getId(), $request->request->get('_csrf_token'))) {
            $em->remove($releve);
            $em->flush();
        }

        return $this->redirectToRoute('app_releves');
    }
}

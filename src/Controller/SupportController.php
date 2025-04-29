<?php

namespace App\Controller;

use App\Entity\Support;
use App\Form\SupportType;
use App\Repository\SupportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/support')]
final class SupportController extends AbstractController
{
    #[Route('/', name: 'app_support_index', methods: ['GET'])]
    public function index(SupportRepository $supportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $supports = $supportRepository->findAll();
        return $this->render('support/index.html.twig', [
            'supports' => $supports,
        ]);
    }

    #[Route('/new', name: 'app_support_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $support = new Support();
        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($support);
            $entityManager->flush();

            $this->addFlash('success', 'Support créé avec succès.');
            return $this->redirectToRoute('app_support_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('support/new.html.twig', [
            'support' => $support,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_support_show', methods: ['GET'])]
    public function show(?Support $support): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$support) {
            $this->addFlash('error', 'Support non trouvé.');
            return $this->redirectToRoute('app_support_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('support/show.html.twig', [
            'support' => $support,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_support_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Support $support, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$support) {
            $this->addFlash('error', 'Support non trouvé.');
            return $this->redirectToRoute('app_support_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Support mis à jour avec succès.');
            return $this->redirectToRoute('app_support_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('support/edit.html.twig', [
            'support' => $support,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_support_delete', methods: ['POST'])]
    public function delete(Request $request, ?Support $support, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$support) {
            $this->addFlash('error', 'Support non trouvé.');
            return $this->redirectToRoute('app_support_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$support->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($support);
            $entityManager->flush();
            $this->addFlash('success', 'Support supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_support_index', [], Response::HTTP_SEE_OTHER);
    }
}
<?php

namespace App\Controller;

use App\Entity\Supportpermission;
use App\Form\SupportpermissionType;
use App\Repository\SupportpermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/supportpermission')]
final class SupportpermissionController extends AbstractController
{
    #[Route(name: 'app_supportpermission_index', methods: ['GET'])]
    public function index(SupportpermissionRepository $supportpermissionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('supportpermission/index.html.twig', [
            'supportpermissions' => $supportpermissionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_supportpermission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $supportpermission = new Supportpermission();
        $form = $this->createForm(SupportpermissionType::class, $supportpermission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supportpermission);
            $entityManager->flush();

            $this->addFlash('success', 'Permission de support créée avec succès.');
            return $this->redirectToRoute('app_supportpermission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supportpermission/new.html.twig', [
            'supportpermission' => $supportpermission,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_supportpermission_show', methods: ['GET'])]
    public function show(?Supportpermission $supportpermission): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$supportpermission) {
            $this->addFlash('error', 'Permission de support non trouvée.');
            return $this->redirectToRoute('app_supportpermission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supportpermission/show.html.twig', [
            'supportpermission' => $supportpermission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supportpermission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Supportpermission $supportpermission, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$supportpermission) {
            $this->addFlash('error', 'Permission de support non trouvée.');
            return $this->redirectToRoute('app_supportpermission_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(SupportpermissionType::class, $supportpermission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Permission de support mise à jour avec succès.');
            return $this->redirectToRoute('app_supportpermission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supportpermission/edit.html.twig', [
            'supportpermission' => $supportpermission,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_supportpermission_delete', methods: ['POST'])]
    public function delete(Request $request, ?Supportpermission $supportpermission, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$supportpermission) {
            $this->addFlash('error', 'Permission de support non trouvée.');
            return $this->redirectToRoute('app_supportpermission_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$supportpermission->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($supportpermission);
            $entityManager->flush();
            $this->addFlash('success', 'Permission de support supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_supportpermission_index', [], Response::HTTP_SEE_OTHER);
    }
}
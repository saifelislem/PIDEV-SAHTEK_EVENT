<?php

namespace App\Controller;

use App\Entity\Support;
use App\Form\SupportType;
use App\Repository\SupportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/support')]
final class SupportController extends AbstractController
{
    #[Route('/', name: 'app_support_index', methods: ['GET'])]
    public function index(SupportRepository $supportRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Get sort and order from query parameters
        $sort = $request->query->get('sort', 'titre');
        $order = $request->query->get('order', 'asc');

        // Fetch supports with sorting
        $supports = $supportRepository->findAllWithSort($sort, $order);

        return $this->render('support/index.html.twig', [
            'supports' => $supports,
            'current_sort' => $sort,
            'current_order' => $order,
        ]);
    }

    #[Route('/new', name: 'app_support_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $support = new Support();
        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('fichier')->getData();

            if ($fichier) {
                $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^a-zA-Z0-9-_]/', '_', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fichier->guessExtension();

                try {
                    $fichier->move(
                        $this->getParameter('supports_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                }

                $support->setUrl('/Uploads/supports/' . $newFilename);
            }

            $entityManager->persist($support);
            $entityManager->flush();

            $this->addFlash('success', 'Support ajouté avec succès.');
            return $this->redirectToRoute('app_support_index');
        }

        return $this->render('support/new.html.twig', [
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
    public function delete(Request $request, Support $support, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Supprimer d'abord toutes les permissions associées
        foreach ($support->getSupportpermissions() as $permission) {
            $entityManager->remove($permission);
        }

        if ($this->isCsrfTokenValid('delete'.$support->getId(), $request->request->get('_token'))) {
            $entityManager->remove($support);
            $entityManager->flush();
            $this->addFlash('success', 'Support supprimé avec succès.');
        }

        return $this->redirectToRoute('app_support_index');
    }
}
<?php

namespace App\Controller;

use App\Entity\Produitsponsoring;
use App\Form\ProduitsponsoringType;
use App\Repository\ProduitsponsoringRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/produitsponsoring')]
final class ProduitsponsoringController extends AbstractController
{
    #[Route(name: 'app_produitsponsoring_index', methods: ['GET'])]
    public function index(ProduitsponsoringRepository $produitsponsoringRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('produitsponsoring/index.html.twig', [
            'produitsponsorings' => $produitsponsoringRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produitsponsoring_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $produitsponsoring = new Produitsponsoring();
        $form = $this->createForm(ProduitsponsoringType::class, $produitsponsoring);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $produitsponsoring->setImage($newFilename);
                    $this->addFlash('success', 'Image uploaded: ' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
                    return $this->render('produitsponsoring/new.html.twig', [
                        'produitsponsoring' => $produitsponsoring,
                        'form' => $form->createView(),
                    ]);
                }
            }

            $entityManager->persist($produitsponsoring);
            $entityManager->flush();

            $this->addFlash('success', 'Produit de sponsoring créé avec succès !');
            return $this->redirectToRoute('app_produitsponsoring_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produitsponsoring/new.html.twig', [
            'produitsponsoring' => $produitsponsoring,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_produitsponsoring_show', methods: ['GET'])]
    public function show(?Produitsponsoring $produitsponsoring): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$produitsponsoring) {
            $this->addFlash('error', 'Produit non trouvé.');
            return $this->redirectToRoute('app_produitsponsoring_index');
        }
        return $this->render('produitsponsoring/show.html.twig', [
            'produitsponsoring' => $produitsponsoring,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produitsponsoring_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Produitsponsoring $produitsponsoring, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$produitsponsoring) {
            $this->addFlash('error', 'Produit non trouvé.');
            return $this->redirectToRoute('app_produitsponsoring_index');
        }

        $form = $this->createForm(ProduitsponsoringType::class, $produitsponsoring);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $produitsponsoring->setImage($newFilename);
                    $this->addFlash('success', 'Image updated: ' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
                    return $this->render('produitsponsoring/edit.html.twig', [
                        'produitsponsoring' => $produitsponsoring,
                        'form' => $form->createView(),
                    ]);
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Produit mis à jour avec succès !');
            return $this->redirectToRoute('app_produitsponsoring_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produitsponsoring/edit.html.twig', [
            'produitsponsoring' => $produitsponsoring,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_produitsponsoring_delete', methods: ['POST'])]
    public function delete(Request $request, ?Produitsponsoring $produitsponsoring, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$produitsponsoring) {
            $this->addFlash('error', 'Produit non trouvé.');
            return $this->redirectToRoute('app_produitsponsoring_index');
        }

        if ($this->isCsrfTokenValid('delete'.$produitsponsoring->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produitsponsoring);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé avec succès !');
        }

        return $this->redirectToRoute('app_produitsponsoring_index', [], Response::HTTP_SEE_OTHER);
    }
}
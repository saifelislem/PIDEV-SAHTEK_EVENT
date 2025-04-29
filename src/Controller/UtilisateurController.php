<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(name: 'app_utilisateur_index', methods: ['GET', 'POST'])]
    public function index(UtilisateurRepository $utilisateurRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Gestion de l'activation/désactivation
        if ($request->isMethod('POST') && $request->request->has('toggle_status')) {
            $id = $request->request->get('id');
            $user = $utilisateurRepository->find($id);
            if ($user) {
                $newStatus = $request->request->get('status') === '1';
                $user->setStatut($newStatus);
                $entityManager->flush();
                $this->addFlash('success', 'Statut de l\'utilisateur mis à jour.');
            } else {
                $this->addFlash('error', 'Utilisateur non trouvé.');
            }
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        // Gestion du tri
        $sort = $request->query->get('sort', 'nom');
        $order = $request->query->get('order', 'asc');
        $validSorts = ['id', 'nom', 'role', 'statut'];
        $sort = in_array($sort, $validSorts) ? $sort : 'nom';
        $order = in_array(strtolower($order), ['asc', 'desc']) ? $order : 'asc';

        $utilisateurs = $utilisateurRepository->findAllSorted($sort, $order);

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'current_sort' => $sort,
            'current_order' => $order,
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('mot_de_passe')->getData();
            if ($plainPassword) {
                $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
                $utilisateur->setMotDePasse($hashedPassword);
            }

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(?Utilisateur $utilisateur): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$utilisateur) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$utilisateur) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('mot_de_passe')->getData();
            if ($plainPassword) {
                $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
                $utilisateur->setMotDePasse($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succès.');
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, ?Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$utilisateur) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
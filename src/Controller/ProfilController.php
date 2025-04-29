<?php
namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Utilisateur;
use App\Form\ProfilType;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/me', name: 'app_profil_me', methods: ['GET'])]
    public function me(EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à votre profil.');
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            $this->addFlash('error', 'Utilisateur invalide.');
            return $this->redirectToRoute('app_login');
        }

        // Redirection pour les admins
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_evenement_index'); // Dashboard admin
        }

        $profil = $user->getProfil();
        if (!$profil) {
            $profil = new Profil();
            $profil->setUtilisateur($user);
            $profil->setEmail($user->getEmail());
            try {
                $entityManager->persist($profil);
                $entityManager->flush();
                $this->addFlash('warning', 'Veuillez compléter votre profil.');
                return $this->redirectToRoute('app_profil_edit', ['id' => $profil->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création du profil : ' . $e->getMessage());
                return $this->render('profil/me.html.twig', [
                    'profil' => null,
                    'user' => $user,
                ]);
            }
        }

        return $this->render('profil/me.html.twig', [
            'profil' => $profil,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Profil $profil, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        if ($profil->getUtilisateur() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce profil.');
        }

        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('app_profil_me');
        }

        return $this->render('profil/edit.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
        ]);
    }
}
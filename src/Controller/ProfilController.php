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

    #[Route('/profil/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $profil = $this->getUser()->getProfil(); // Supposons que Profil est lié à l'utilisateur
        if (!$profil) {
            throw $this->createNotFoundException('Profil non trouvé.');
        }

        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wasSubscribed = $profil->getNewsletter(); // État avant modification
            $entityManager->persist($profil);
            $entityManager->flush();

            // Envoi de l'e-mail de confirmation
            $email = (new Email())
                ->from('amine69souib@gmail.com')
                ->to($profil->getEmail()) // Assurez-vous que Profil a une méthode getEmail()
                ->subject('Mise à jour de votre profil')
                ->text('Bonjour, votre profil a été mis à jour avec succès sur notre site.');

            $mailer->send($email);

            // Gestion de la newsletter
            if (!$wasSubscribed && $profil->getNewsletter()) {
                $this->addFlash('success', 'Vous êtes maintenant inscrit à la newsletter.');
                // Optionnel : envoyer un e-mail supplémentaire pour la newsletter
                $newsletterEmail = (new Email())
                    ->from('amine69souib@gmail.com')
                    ->to($profil->getEmail())
                    ->subject('Bienvenue à notre newsletter')
                    ->text('Vous êtes désormais inscrit à notre newsletter. Restez à l’écoute pour les dernières nouvelles !');
                $mailer->send($newsletterEmail);
            }

            $this->addFlash('success', 'Profil mis à jour avec succès. Un e-mail de confirmation vous a été envoyé.');
            return $this->redirectToRoute('app_profil_edit');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/test-email', name: 'test_email')]
    public function testEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('amine69souib@gmail.com')
            ->to('amine69souib@gmail.com')
            ->subject('Test depuis contrôleur')
            ->text('Ceci est un test.');
        try {
            $mailer->send($email);
            return new Response('E-mail envoyé avec succès !');
        } catch (\Exception $e) {
            return new Response('Erreur : ' . $e->getMessage());
        }
    }

}
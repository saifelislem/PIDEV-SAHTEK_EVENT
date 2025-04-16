<?php
namespace App\Controller;

use App\Entity\SponsorPending;
use App\Entity\Utilisateur;
use App\Entity\Evenement;
use App\Entity\Reclamation;
use App\Entity\ContratSponsoring;
use App\Service\SponsorValidationService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_dashboard');
            }
            return $this->redirectToRoute('app_profil_me');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/account-disabled', name: 'app_account_disabled')]
    public function accountDisabled(): Response
    {
        return $this->render('security/account_disabled.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('front/SahtekEvent.html.twig');
    }



    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profil_edit', ['id' => $this->getUser()->getProfil()->getId()]);
        }

        $form = $this->createForm(ForgotPassType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('warning', 'Cet email n\'est pas associé à un compte.');
                return $this->redirectToRoute('app_login');
            }

            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);

            $entityManager->flush();

            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new TemplatedEmail())
                ->from('contact@votredomaine.com')
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->htmlTemplate('security/reset_password_email.html.twig')
                ->context([
                    'url' => $url,
                    'emailTitle' => 'Réinitialisation de mot de passe',
                    'emailDesc' => 'Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le lien ci-dessous :',
                    'btnTitle' => 'Réinitialiser mon mot de passe',
                ]);

            try {
                $mailer->send($email);
                $this->addFlash('success', 'Un lien de réinitialisation a été envoyé à votre email.');
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Erreur lors de l\'envoi de l\'email. Réessayez plus tard.');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgot_password.html.twig', [
            'title' => 'Mot de passe oublié',
            'forgotPassForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(string $token, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profil_edit', ['id' => $this->getUser()->getProfil()->getId()]);
        }

        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $this->addFlash('warning', 'Token invalide ou expiré.');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('mot_de_passe')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setMotDePasse($hashedPassword);
            $user->setResetToken(null);

            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'title' => 'Nouveau mot de passe',
            'resetPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/topbar', name: 'app_admin_topbar')]
    public function renderTopbar(NotificationService $notificationService): Response
    {
        return $this->render('security/_topbar.html.twig', [
            'pending_count' => $notificationService->getPendingCount(),
            'pending_products' => $notificationService->getPendingProducts(),
        ]);
    }



    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(EntityManagerInterface $entityManager, SponsorValidationService $validationService, Request $request, NotificationService $notificationService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Gestion de la validation
        if ($request->isMethod('POST') && $request->request->has('validate')) {
            $id = $request->request->get('id');
            $sponsorPending = $entityManager->getRepository(SponsorPending::class)->find($id);
            if ($sponsorPending && $sponsorPending->getStatus() === 'pending') {
                try {
                    $validationService->validateSponsor($sponsorPending);
                    $this->addFlash('success', 'Demande validée avec succès.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la validation : ' . $e->getMessage());
                }
            }
            return $this->redirectToRoute('app_admin_dashboard');
        }

        // Gestion du rejet
        if ($request->isMethod('POST') && $request->request->has('reject')) {
            $id = $request->request->get('id');
            $sponsorPending = $entityManager->getRepository(SponsorPending::class)->find($id);
            if ($sponsorPending && $sponsorPending->getStatus() === 'pending') {
                $sponsorPending->setStatus('rejected');
                $entityManager->flush();
                $this->addFlash('success', 'Demande rejetée.');
            }
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $userCount = $entityManager->getRepository(Utilisateur::class)->count([]);
        $eventCount = $entityManager->getRepository(Evenement::class)->count([]);
        $reclamationCount = $entityManager->getRepository(Reclamation::class)->count([]);
        $sponsoringCount = $entityManager->getRepository(ContratSponsoring::class)->count([]);
        $pendingCount = $notificationService->getPendingCount();
        $pendingProducts = $notificationService->getPendingProducts();
        $demandes = $entityManager->getRepository(SponsorPending::class)->findBy(['status' => 'pending']);

        return $this->render('security/Dashboard.html.twig', [
            'user_count' => $userCount,
            'event_count' => $eventCount,
            'reclamation_count' => $reclamationCount,
            'sponsoring_count' => $sponsoringCount,
            'pending_count' => $pendingCount,
            'pending_products' => $pendingProducts,
            'demandes' => $demandes,
        ]);
    }
}
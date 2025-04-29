<?php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Evenement;
use App\Entity\Reclamation;
use App\Entity\ContratSponsoring;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // If the user is already logged in, redirect based on role
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_dashboard'); // Define this route
            }
            return $this->redirectToRoute('app_evenement_index_front');
        }

        // Get login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
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
    public function forgotPassword(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $this->addFlash('success', 'Un lien de réinitialisation a été envoyé à ' . $email);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/forgot-password.html.twig');
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userCount = $entityManager->getRepository(Utilisateur::class)->count([]);
        $eventCount = $entityManager->getRepository(Evenement::class)->count([]);
        $reclamationCount = $entityManager->getRepository(Reclamation::class)->count([]);
        $sponsoringCount = $entityManager->getRepository(ContratSponsoring::class)->count([]);

        return $this->render('security/Dashboard.html.twig', [
            'user_count' => $userCount,
            'event_count' => $eventCount,
            'reclamation_count' => $reclamationCount,
            'sponsoring_count' => $sponsoringCount,
        ]);
    }
}
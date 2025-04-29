<?php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Profil;
use App\Form\RegistrationFormType;
use App\Service\FaceAuthService;  // Importation du service FaceAuthService
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        FaceAuthService $faceAuthService  // Injection du service FaceAuthService
    ): Response {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('mot_de_passe')->getData());
            $user->setMotDePasse($hashedPassword);

            // Traitement des données faciales
            $faceData = $form->get('faceData')->getData();  // Récupérer les données faciales (base64 ou fichier)
            if ($faceData) {
                try {
                    // Trouver les utilisateurs correspondants via le service de reconnaissance faciale
                    $matchingUsers = $faceAuthService->findMatchingUsers($faceData);
                    if (!empty($matchingUsers)) {
                        // Si une correspondance est trouvée, afficher un message ou gérer autrement
                        $this->addFlash('warning', 'Cette face correspond à un autre utilisateur.');
                    } else {
                        // Enregistrer les données faciales pour l'utilisateur
                        $user->setFaceData($faceData);
                    }
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du traitement des données faciales.');
                }
            }

            // Créer un profil vide avec l'email
            $profil = new Profil();
            $profil->setUtilisateur($user);
            $profil->setEmail($user->getEmail());

            // Persister l'utilisateur et le profil
            $entityManager->persist($user);
            $entityManager->persist($profil);
            $entityManager->flush();

            // Connecter automatiquement l'utilisateur
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            // Rediriger vers la page d'édition du profil
            $this->addFlash('success', 'Inscription réussie ! Veuillez compléter votre profil.');
            return $this->redirectToRoute('app_profil_edit', ['id' => $profil->getId()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

<?php
namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Feedback;
use App\Entity\Participation;
use App\Entity\Reclamation;
use App\Entity\Support;
use App\Form\EvenementType;
use App\Form\FeedbackType;
use App\Form\ParticipationType;
use App\Form\ReclamationType;
use App\Form\UserParticipationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index_front')]
    public function frontIndex(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager->getRepository(Evenement::class)->findAll();

        $eventsByLocation = [];
        foreach ($evenements as $evenement) {
            $location = $evenement->getLieu() ?? 'Unknown';
            if (!isset($eventsByLocation[$location])) {
                $eventsByLocation[$location] = [
                    'events' => [],
                    'eventCount' => 0,
                    'participantCount' => 0,
                ];
            }
            $eventsByLocation[$location]['events'][] = $evenement;
            $eventsByLocation[$location]['eventCount']++;
            $eventsByLocation[$location]['participantCount'] += $evenement->getParticipations()->count();
        }

        return $this->render('front/SahtekEvent.html.twig', [
            'events_by_location' => $eventsByLocation,
        ]);
    }

    #[Route('/admin/evenement', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $evenements = $entityManager->getRepository(Evenement::class)->findAll();
        return $this->render('evenement/index.html.twig', ['evenements' => $evenements]);
    }

    #[Route('/admin/evenement/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement, [
            'attr' => ['novalidate' => 'novalidate']
        ]);
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
                    $evenement->setImage($newFilename);
                    $this->addFlash('success', 'Image uploaded: ' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
                    return $this->render('evenement/new.html.twig', [
                        'evenement' => $evenement,
                        'form' => $form->createView(),
                    ]);
                }
            }

            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'Événement créé avec succès !');
            return $this->redirectToRoute('app_evenement_index');
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    // Note: You’re missing the show action entirely in this version! Adding it back.
    #[Route('/admin/evenement/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(?Evenement $evenement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$evenement) {
            $this->addFlash('error', 'Événement non trouvé, connard.');
            return $this->redirectToRoute('app_evenement_index');
        }

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/admin/evenement/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$evenement) {
            $this->addFlash('error', 'Événement non trouvé.');
            return $this->redirectToRoute('app_evenement_index');
        }

        $form = $this->createForm(EvenementType::class, $evenement);
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
                    $evenement->setImage($newFilename);
                    $this->addFlash('success', 'Image updated: ' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
                    return $this->render('evenement/edit.html.twig', [
                        'evenement' => $evenement,
                        'form' => $form->createView(),
                    ]);
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Événement mis à jour avec succès !');
            return $this->redirectToRoute('app_evenement_index');
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/{id}/participate', name: 'app_evenement_participate', methods: ['GET', 'POST'])]
    public function participate(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('ROLE_USER');
        $utilisateur = $this->getUser();

        // Créer une nouvelle participation
        $participation = new Participation();
        $participation->setEvenement($evenement);
        $participation->setUtilisateur($utilisateur);
        $participation->setDateInscription(new \DateTime()); // Date de confirmation actuelle

        $form = $this->createForm(UserParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation pour ' . $evenement->getNom() . ' a été confirmée avec succès !');
            return $this->redirectToRoute('app_evenement_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/reservation.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/payment/process/{participationId}', name: 'app_payment_process', methods: ['GET'])]
    public function paymentProcess(int $participationId, EntityManagerInterface $entityManager): Response
    {
        $participation = $entityManager->getRepository(Participation::class)->find($participationId);
        if (!$participation) {
            $this->addFlash('error', 'Participation non trouvée.');
            return $this->redirectToRoute('app_evenement_index_front');
        }

        $this->addFlash('info', 'Paiement en cours pour ' . $participation->getEvenement()->getNom() . ' avec ' . $participation->getMoyenPaiement());
        return $this->redirectToRoute('app_evenement_index_front');
    }

    #[Route('/my-participations', name: 'app_my_participations', methods: ['GET'])]
    public function myParticipations(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $participations = $entityManager->getRepository(Participation::class)->findBy(['utilisateur' => $user]);

        return $this->render('front/my_participations.html.twig', [
            'participations' => $participations,
        ]);
    }

    #[Route('/evenement/{id}/details', name: 'app_evenement_details', methods: ['GET', 'POST'])]
    public function showDetails(Request $request, ?Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$evenement) {
            $this->addFlash('error', 'Événement non trouvé.');
            return $this->redirectToRoute('app_evenement_index_front');
        }

        $user = $this->getUser();
        $participation = $entityManager->getRepository(Participation::class)->findOneBy([
            'utilisateur' => $user,
            'evenement' => $evenement,
        ]);

        if (!$participation) {
            throw $this->createAccessDeniedException('Vous n’êtes pas inscrit à cet événement.');
        }

        $supports = $entityManager->getRepository(Support::class)->findBy(['evenement' => $evenement]);

        // Formulaire de réclamation
        $reclamation = new Reclamation();
        $reclamation->setUtilisateur($user);
        $reclamation->setEvenement($evenement);
        $reclamation->setStatut('en_attente'); // Statut par défaut
        $reclamationForm = $this->createForm(ReclamationType::class, $reclamation, ['attr' => ['name' => 'reclamation_form']]);
        $reclamationForm->handleRequest($request);

        // Formulaire de feedback
        $feedback = new Feedback();
        $feedbackForm = $this->createForm(FeedbackType::class, $feedback, ['attr' => ['name' => 'feedback_form']]);
        $feedbackForm->handleRequest($request);

        // Traitement de la réclamation
        if ($reclamationForm->isSubmitted() && $reclamationForm->isValid()) {
            $imageFile = $reclamationForm->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('images_directory'), $newFilename);
                $reclamation->setImage($newFilename);
            } else {
                $this->addFlash('error', 'Veuillez sélectionner une image.');
                return $this->render('front/evenement_show.html.twig', [
                    'evenement' => $evenement,
                    'supports' => $supports,
                    'reclamation_form' => $reclamationForm->createView(),
                    'feedback_form' => $feedbackForm->createView(),
                ]);
            }

            $entityManager->persist($reclamation);
            $entityManager->flush();
            $this->addFlash('success', 'Votre réclamation a été soumise avec succès.');
            return $this->redirectToRoute('app_evenement_details', ['id' => $evenement->getId()]);
        }

        // Traitement du feedback (simplifié)
        if ($feedbackForm->isSubmitted() && $feedbackForm->isValid()) {
            $entityManager->persist($feedback);
            $entityManager->flush();
            $this->addFlash('success', 'Votre feedback a été soumis avec succès.');
            return $this->redirectToRoute('app_evenement_details', ['id' => $evenement->getId()]);
        }

        return $this->render('front/evenement_show.html.twig', [
            'evenement' => $evenement,
            'supports' => $supports,
            'reclamation_form' => $reclamationForm->createView(),
            'feedback_form' => $feedbackForm->createView(),
        ]);
    }

    #[Route('/evenement/{id}/supports', name: 'app_evenement_supports', methods: ['GET'])]
    public function supports(?Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$evenement) {
            $this->addFlash('error', 'Événement non trouvé.');
            return $this->redirectToRoute('app_evenement_index_front');
        }

        $user = $this->getUser();
        $participation = $entityManager->getRepository(Participation::class)->findOneBy([
            'utilisateur' => $user,
            'evenement' => $evenement,
        ]);

        if (!$participation) {
            throw $this->createAccessDeniedException('Vous n’êtes pas inscrit à cet événement.');
        }

        $supports = $entityManager->getRepository(Support::class)->findBy(['evenement' => $evenement]);

        return $this->render('front/evenement_support.html.twig', [
            'evenement' => $evenement,
            'supports' => $supports,
        ]);
    }
}
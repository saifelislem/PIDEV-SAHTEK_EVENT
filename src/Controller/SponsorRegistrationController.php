<?php
namespace App\Controller;

use App\Entity\SponsorPending;
use App\Form\SponsorRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SponsorRegistrationController extends AbstractController
{
    #[Route('/register/sponsor', name: 'app_register_sponsor', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sponsorPending = new SponsorPending();
        $form = $this->createForm(SponsorRegistrationType::class, $sponsorPending);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $imageFile = $form->get('produitImage')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $sponsorPending->setProduitImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image.');
                    return $this->redirectToRoute('app_register_sponsor');
                }
            }

            // Enregistrer dans SponsorPending
            $entityManager->persist($sponsorPending);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande de sponsoring a été envoyée ! Elle sera examinée par l’admin.');
            return $this->redirectToRoute('app_register_sponsor');
        }

        return $this->render('sponsor/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
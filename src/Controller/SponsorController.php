<?php
namespace App\Controller;

use App\Entity\SponsorPending;
use App\Form\ProduitSponsorType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SponsorController extends AbstractController
{
    #[Route('/sponsor/produit/new', name: 'app_sponsor_produit_new')]
    public function newProduit(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SPONSOR');

        $sponsorPending = new SponsorPending();
        $user = $this->getUser();
        $sponsorPending->setNom($user->getNom());
        $sponsorPending->setPrenom($user->getPrenom());
        $sponsorPending->setEmail($user->getEmail());
        $sponsorPending->setNationalite($user->getNationalite());
        $sponsorPending->setGenre($user->getGenre());
        $sponsorPending->setStatus('pending');

        $form = $this->createForm(ProduitSponsorType::class, $sponsorPending);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload d'image
            $imageFile = $form->get('produitImage')->getData();
            if ($imageFile) {
                $imageName = $fileUploader->upload($imageFile);
                $sponsorPending->setProduitImage($imageName);
            }

            $entityManager->persist($sponsorPending);
            $entityManager->flush();

            $this->addFlash('success', 'Produit soumis avec succès. En attente de validation.');
            return $this->redirectToRoute('app_sponsor_produit_new');
        }

        return $this->render('sponsor/produit_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
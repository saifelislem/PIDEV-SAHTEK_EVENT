<?php
namespace App\Controller;

use App\Entity\SponsorPending;
use App\Service\SponsorValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sponsor')]
class AdminSponsorController extends AbstractController
{
    #[Route('/{id}', name: 'app_admin_sponsor_detail', requirements: ['id' => '\d+'])]
    public function detail(SponsorPending $sponsorPending): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/sponsor/detail.html.twig', [
            'demande' => $sponsorPending,
        ]);
    }

    #[Route('/{id}/validate', name: 'app_admin_sponsor_validate', requirements: ['id' => '\d+'])]
    public function validate(SponsorPending $sponsorPending, SponsorValidationService $validationService, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        try {
            $validationService->validateSponsor($sponsorPending);
            $this->addFlash('success', 'Demande validée avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la validation : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/{id}/reject', name: 'app_admin_sponsor_reject', requirements: ['id' => '\d+'])]
    public function reject(SponsorPending $sponsorPending, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $sponsorPending->setStatus('rejected');
        $entityManager->flush();

        $this->addFlash('success', 'Demande rejetée.');

        return $this->redirectToRoute('app_admin_dashboard');
    }
}
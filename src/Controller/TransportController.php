<?php

namespace App\Controller;

use App\Entity\Transport;
use App\Form\TransportType;
use App\Repository\TransportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transport')]
final class TransportController extends AbstractController
{
    #[Route(name: 'app_transport_index', methods: ['GET'])]
    public function index(Request $request, TransportRepository $transportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Gestion du tri
        $sort = $request->query->get('sort', 'vehicule');
        $order = $request->query->get('order', 'asc');
        $validSorts = ['vehicule', 'date'];
        $sort = in_array($sort, $validSorts) ? $sort : 'vehicule';
        $order = in_array(strtolower($order), ['asc', 'desc']) ? $order : 'asc';

        $transports = $transportRepository->findAllSorted($sort, $order);

        return $this->render('transport/index.html.twig', [
            'transports' => $transports,
            'current_sort' => $sort,
            'current_order' => $order,
        ]);
    }

    #[Route('/new', name: 'app_transport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $transport = new Transport();
        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transport);
            $entityManager->flush();

            $this->addFlash('success', 'Transport créé avec succès.');
            return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transport/new.html.twig', [
            'transport' => $transport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_transport_show', methods: ['GET'])]
    public function show(?Transport $transport): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$transport) {
            $this->addFlash('error', 'Transport non trouvé.');
            return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transport/show.html.twig', [
            'transport' => $transport,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Transport $transport, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$transport) {
            $this->addFlash('error', 'Transport non trouvé.');
            return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Transport mis à jour avec succès.');
            return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transport/edit.html.twig', [
            'transport' => $transport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_transport_delete', methods: ['POST'])]
    public function delete(Request $request, ?Transport $transport, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$transport) {
            $this->addFlash('error', 'Transport non trouvé.');
            return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$transport->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transport);
            $entityManager->flush();
            $this->addFlash('success', 'Transport supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
    }
}
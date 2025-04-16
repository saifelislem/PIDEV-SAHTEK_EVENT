<?php
// src/Service/NotificationService.php
namespace App\Service;

use App\Entity\SponsorPending;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Compte les produits sponsoring en attente.
     */
    public function getPendingCount(): int
    {
        return $this->entityManager->getRepository(SponsorPending::class)
            ->createQueryBuilder('sp')
            ->select('COUNT(sp.id)')
            ->where('sp.status = :status')
            ->setParameter('status', 'pending')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère la liste des produits en attente.
     */
    public function getPendingProducts(): array
    {
        return $this->entityManager->getRepository(SponsorPending::class)
            ->createQueryBuilder('sp')
            ->where('sp.status = :status')
            ->setParameter('status', 'pending')
            ->orderBy('sp.id', 'DESC') // Changé de createdAt à id
            ->setMaxResults(5) // Limite à 5 pour le dropdown
            ->getQuery()
            ->getResult();
    }
}
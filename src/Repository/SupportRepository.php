<?php

namespace App\Repository;

use App\Entity\Support;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Support>
 */
class SupportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Support::class);
    }

    /**
     * Find all supports with sorting.
     *
     * @param string $sort Field to sort by (id, titre, type)
     * @param string $order Sort order (asc, desc)
     * @return Support[]
     */
    public function findAllWithSort(string $sort = 'titre', string $order = 'asc'): array
    {
        $qb = $this->createQueryBuilder('s');

        // Validate sort field
        $allowedSorts = ['id', 'titre', 'type'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'titre'; // Default
        }

        // Validate order
        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';

        // Apply sorting
        $qb->orderBy('s.' . $sort, $order);

        return $qb->getQuery()->getResult();
    }
}
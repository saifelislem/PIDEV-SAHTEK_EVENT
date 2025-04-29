<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Find all services with sorting.
     *
     * @param string $sort Field to sort by (default, type, cout)
     * @param string $order Sort order (asc, desc)
     * @return Service[]
     */
    public function findAllSorted(string $sort = 'default', string $order = 'asc'): array
    {
        $qb = $this->createQueryBuilder('s');

        // Validate sort field
        $allowedSorts = ['default', 'type', 'cout'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'default';
        }

        // Map 'default' to 'type'
        $sortField = $sort === 'default' ? 'type' : $sort;

        // Validate order
        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';

        // Apply sorting
        $qb->orderBy('s.' . $sortField, $order);

        return $qb->getQuery()->getResult();
    }
}
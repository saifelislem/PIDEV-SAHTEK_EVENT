<?php

namespace App\Repository;

use App\Entity\Supportpermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Supportpermission>
 */
class SupportpermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supportpermission::class);
    }

    /**
     * Find all support permissions with sorting.
     *
     * @param string $sort Field to sort by (permissionType, startDate, endDate)
     * @param string $order Sort order (asc, desc)
     * @return Supportpermission[]
     */
    public function findAllWithSort(string $sort = 'permissionType', string $order = 'asc'): array
    {
        $qb = $this->createQueryBuilder('sp');

        // Validate sort field
        $allowedSorts = ['permission_type', 'startDate', 'endDate'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'permission_type'; // Default
        }

        // Validate order
        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';

        // Apply sorting
        $qb->orderBy('sp.' . $sort, $order);

        return $qb->getQuery()->getResult();
    }
}
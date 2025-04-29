<?php
namespace App\Repository;

use App\Entity\Transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transport>
 */
class TransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transport::class);
    }

    /**
     * @return Transport[] Returns an array of Transport objects sorted by the specified field and direction
     */
    public function findAllSorted(string $sort, string $order): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->leftJoin('t.evenement', 'e')
            ->leftJoin('t.service', 's');

        switch ($sort) {
            case 'vehicule':
                $queryBuilder->orderBy('t.vehicule', $order);
                break;
            case 'date':
                $queryBuilder->orderBy('t.date', $order);
                break;
            default:
                $queryBuilder->orderBy('t.vehicule', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
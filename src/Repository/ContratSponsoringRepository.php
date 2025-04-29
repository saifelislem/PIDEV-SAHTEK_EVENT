<?php
namespace App\Repository;

use App\Entity\ContratSponsoring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContratSponsoring>
 */
class ContratSponsoringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContratSponsoring::class);
    }

    /**
     * @return ContratSponsoring[] Returns an array of ContratSponsoring objects sorted by the specified field and direction
     */
    public function findAllSorted(string $sort, string $order): array
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->leftJoin('c.utilisateur', 'u')
            ->leftJoin('c.evenement', 'e')
            ->leftJoin('c.produitsponsorings', 'p');

        switch ($sort) {
            case 'id':
                $queryBuilder->orderBy('c.id', $order);
                break;
            case 'montant':
                $queryBuilder->orderBy('c.montant', $order);
                break;
            case 'description':
                $queryBuilder->orderBy('c.description', $order);
                break;
            default:
                $queryBuilder->orderBy('c.id', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
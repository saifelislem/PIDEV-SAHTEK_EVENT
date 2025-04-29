<?php
namespace App\Repository;

use App\Entity\Produitsponsoring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produitsponsoring>
 */
class ProduitsponsoringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produitsponsoring::class);
    }

    /**
     * @return Produitsponsoring[] Returns an array of Produitsponsoring objects sorted by the specified field and direction
     */
    public function findAllSorted(string $sort, string $order): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.utilisateur', 'u')
            ->leftJoin('p.contratSponsorings', 'cs');

        switch ($sort) {
            case 'id':
                $queryBuilder->orderBy('p.id', $order);
                break;
            case 'nom':
                $queryBuilder->orderBy('p.nom', $order);
                break;
            case 'quantite':
                $queryBuilder->orderBy('p.quantite', $order);
                break;
            case 'prix':
                $queryBuilder->orderBy('p.prix', $order);
                break;
            default:
                $queryBuilder->orderBy('p.id', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
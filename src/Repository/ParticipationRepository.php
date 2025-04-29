<?php

namespace App\Repository;

use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participation>
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    /**
     * @return Participation[] Returns an array of Participation objects sorted by the specified field and direction
     */
    public function findAllSorted(string $sort, string $order): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.utilisateur', 'u');

        switch ($sort) {
            case 'id':
                $queryBuilder->orderBy('p.id', $order);
                break;
            case 'nom':
                $queryBuilder->orderBy('u.nom', $order)
                    ->addOrderBy('u.prenom', $order);
                break;
            case 'dateInscription':
                $queryBuilder->orderBy('p.date_inscription', $order);
                break;
            case 'moyenPaiement':
                $queryBuilder->orderBy('p.moyen_paiement', $order);
                break;
            default:
                $queryBuilder->orderBy('u.nom', 'ASC')
                    ->addOrderBy('u.prenom', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
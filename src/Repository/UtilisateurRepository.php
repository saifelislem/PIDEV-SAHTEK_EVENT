<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * @return Utilisateur[] Returns an array of Utilisateur objects sorted by the specified field and direction
     */
    public function findAllSorted(string $sort, string $order): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        switch ($sort) {
            case 'id':
                $queryBuilder->orderBy('u.id', $order);
                break;
            case 'nom':
                $queryBuilder->orderBy('u.nom', $order);
                break;
            case 'role':
                $queryBuilder->orderBy('u.role', $order);
                break;
            case 'statut':
                $queryBuilder->orderBy('u.statut', $order);
                break;
            default:
                $queryBuilder->orderBy('u.nom', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
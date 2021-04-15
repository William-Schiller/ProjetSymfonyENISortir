<?php

namespace App\Repository;

use App\Data\SearchDataCampus;
use App\Entity\Campus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

//Filtre par campus
    public function findWhereCampusWithPage($limit, $numPage, $idCampus)
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'camp')
            ->where('camp.id = :idCampus')
            ->setParameter('idCampus', $idCampus)
            ->setMaxResults($limit)
            ->setFirstResult(($numPage - 1) * $limit);
        $query = $queryBuilder->getQuery();
        return new Paginator($query);
    }

    /**
     * Récupère les campus en lien avec une recherche
     * @return Campus[]
     */
    public function findSearch(SearchDataCampus $search): array {

        $query = $this->createQueryBuilder('c')
            ->where('c.name LIKE :search')
            ->setParameter('search', "%{$search->search}%");

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Campus[] Returns an array of Campus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Campus
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

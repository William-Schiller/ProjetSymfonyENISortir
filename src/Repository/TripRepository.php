<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use UnexpectedValueException;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * @return Trip[] Returns an array of Trip object where the promotor is the app_user
     * and where the status of trip is updatable
     */
    public function findMyTripsNotInProgress(Participant $user)
    {

        return $this->createQueryBuilder('t')
            ->innerJoin('t.status', 's')
            ->where('s.name = :Create')
            ->setParameter('Create', 'Create')
            ->orWhere('s.name = :Active')
            ->andWhere('t.promoter = :idUser')
            ->setParameter('idUser', $user->getId())
            ->setParameter('Active', 'Active')
            ->orderBy('t.dateStart', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithPage($limit, $numPage)
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->setMaxResults($limit)
            ->setFirstResult(($numPage - 1) * $limit);
        $query = $queryBuilder->getQuery();
        return new Paginator($query);
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

    public function nbPages($nbLine)
    {
        $queryBuilder = $this->createQueryBuilder('trip');
        $query = $queryBuilder->getQuery();
        return ceil(count($query->getResult()) / $nbLine);
    }

    // /**
    //  * @return Trip[] Returns an array of Trip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trip
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

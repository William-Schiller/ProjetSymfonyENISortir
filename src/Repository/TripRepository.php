<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function findMyTripsNotInProgress(Participant $user){

        return $this->createQueryBuilder('t')
            ->innerJoin('t.status', 's')
            ->andWhere('t.promoter = :idUser')
            ->setParameter('idUser', $user->getId())
            ->andWhere('s.name = :Create')
            ->setParameter('Create', 'Create')
            ->orWhere('s.name = :Active')
            ->setParameter('Active', 'Active')
            ->orderBy('t.dateStart', 'DESC')
            ->getQuery()
            ->getResult();
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

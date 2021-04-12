<?php

namespace App\Repository;

use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    public function countInscriptionsByTrip(Trip $trip){
        return $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->innerJoin('i.trip', 't')
            ->where('t.id = :idTrip')
            ->setParameter('idTrip', $trip->getId())
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findByParticipantAndTrip(Participant $user, Trip $trip){
        return $this->createQueryBuilder('i')
            ->innerJoin('i.trip', 't')
            ->innerJoin('i.participant', 'p')
            ->where('t.id = :idTrip')
            ->setParameter('idTrip', $trip->getId())
            ->andWhere('p.id = :idUser')
            ->setParameter('idUser', $user->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Inscription[] Returns an array of Inscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inscription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

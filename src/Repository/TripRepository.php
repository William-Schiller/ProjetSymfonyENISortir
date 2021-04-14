<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Participant;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


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

    public function nbPages($nbLine)
    {
        $queryBuilder = $this->createQueryBuilder('trip');
        $query = $queryBuilder->getQuery();
        return ceil(count($query->getResult()) / $nbLine);
    }

    //passer le nombre de lignes à findSearch


    /**
     * Récupère les sorties en lien avec une recherche
     * @return Trip[]
     */
    public function findSearch(SearchData $search, $idCurrentUser): array
    {
        $query = $this
            ->createQueryBuilder('trips')
            ->select('camp', 'trips')
            ->join('trips.campus', 'camp');

        //ChoicesType si l'utilisateur est inscrit
        if (($search->subscribedTo) == 1) {
            $query = $query
                ->join('trips.inscription', 'inscpt')
                ->andWhere('inscpt.participant = :idCurrentUser')
                ->setParameter('idCurrentUser', $idCurrentUser);
        }

        //aller récupérer la list des id de toutes les sorties auxquelles le participant est inscrit
        //utiliser la liste dans un not in dans la requête (NOT IN)
        /*if (($search->subscribedTo) == 2) {
            $query = $query
                ->join('trips.inscription', 'inscpt')
                ->andWhere('inscpt.participant != :idCurrentUser')
                ->andWhere('inscpt.participant NOT IN (:inscription')
                ->setParameter('idCurrentUser', $idCurrentUser);
        }*/


        if (!empty($search->search)) {
            $query = $query
                ->andWhere('trips.name LIKE :search')
                ->setParameter('search', "%{$search->search}%");
        }

        if (!empty($search->campus)) {
            $query = $query
                ->andWhere('trips.campus IN (:campus)')
                ->setParameter('campus', $search->campus);
        }

        if (!empty($search->dateMin)) {
            $query = $query
                ->andWhere('trips.dateStart >= :dateMin')
                ->setParameter('dateMin', $search->dateMin);
        }

        if (!empty($search->dateMax)) {
            $query = $query
                ->andWhere('trips.dateStart <= :dateMax')
                ->setParameter('dateMax', $search->dateMax);
        }

        //ChoicesType si l'utilisateur est organisateur de la sortie
        if (!empty($search->isOrganizer)) {
            $query = $query
                ->andWhere('trips.promoter = :idCurrentUser')
                ->setParameter('idCurrentUser', $idCurrentUser);

        }


        //nbPage, a la fin de la création des filtres, executer la requete sans limiter le nombre de resultat

        return $query->getQuery()->getResult();
    }


}

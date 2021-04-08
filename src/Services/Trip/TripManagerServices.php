<?php


namespace App\Services\Trip;


use App\Controller\TripManageController;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;

class TripManagerServices
{
    protected $entityManager;
    /**
     * TripManagerServices constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Trip $trip
     * @return boolean
     */
    public function checkDatesTrip(Trip $trip) {
        $check = true;
        if($trip->getDateLimitInscription() > $trip->getDateStart()){
            $check = false;
        }
        return $check;
    }

}
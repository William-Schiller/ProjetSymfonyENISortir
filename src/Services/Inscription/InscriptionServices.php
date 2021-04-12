<?php


namespace App\Services\Inscription;


use App\Entity\Participant;
use App\Entity\Trip;
use App\Repository\InscriptionRepository;
use App\Repository\ParticipantRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;

class InscriptionServices
{
    protected $entityManager;
    /**
     * InscriptionServices constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return bool
     */
    public function checkPlaces(Trip $trip, InscriptionRepository $inscriptionRepository){
        $check = true;

        $currentNbParticipant = $inscriptionRepository->countInscriptionsByTrip($trip);
        if($currentNbParticipant >= $trip->getNbMaxRegistration()){
            $check = false;
        }

        return $check;
    }

}
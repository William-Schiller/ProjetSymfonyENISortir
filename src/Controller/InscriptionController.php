<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use App\Repository\ParticipantRepository;
use App\Repository\TripRepository;
use App\Services\Inscription\InscriptionServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InscriptionController
 * @package App\Controller
 * @Route("inscription", name="inscription_")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/s'inscrire/{id}", requirements={"id":"\d+"}, name="register")
     */
    public function register($id, EntityManagerInterface $entityManager, TripRepository $tripRepository,
                        InscriptionRepository $inscriptionRepository, InscriptionServices $inscriptionServices)
    {
        $trip = $tripRepository->findOneBy(['id' => $id]);

        /*
         * Verifier que l'ustilisateur ne soit pas inscrit
         */
        if(!empty($inscriptionRepository->findByParticipantAndTrip($this->getUser(), $trip))){
            $this->addFlash('danger', 'Vous ne pouvez pas vous inscrire plus d\'une fois à une sortie');
            return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
        }
        /*
         * Verifier la date limite d'inscription
         */
        if($trip->getDateLimitInscription()<new \DateTime('now')){
            $this->addFlash('danger', 'La date limite d\'inscription à cette sortie est dépassée');
            return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
        }
        /*
         * Verifier le status de la sortie
         */
        if($trip->getStatus()->getName() != 'Active' ){
            $this->addFlash('danger', 'Le status de la sortie ne permet pas l\'inscription');
            return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
        }
        /*
         * Verifier qu'il reste de la place
         */
        if(!$inscriptionServices->checkPlaces($trip, $inscriptionRepository)){
            $this->addFlash('danger', 'Impossible : le nombre maximum de participants est atteint');
            return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
        }

        $inscription = new Inscription();

        $inscription->setParticipant($this->getUser());
        $inscription->setTrip($trip);

        $entityManager->persist($inscription);
        $entityManager->flush();

        $this->addFlash('success', 'Vous êtes inscrit à la sortie "' . $trip->getName() . '"');

        return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
    }

    /**
     * @Route("/seDesinscrire/{id}", requirements={"id":"\d+"}, name="unsubscribe")
     */
    public function unsubscribe($id, EntityManagerInterface $entityManager, TripRepository $tripRepository,
                             InscriptionRepository $inscriptionRepository)
    {
        $trip = $tripRepository->findOneBy(['id' => $id]);

        /*
         * Verifier que l'ustilisateur soit inscrit
         */
        if(empty($inscriptionRepository->findByParticipantAndTrip($this->getUser(), $trip))){
            $this->addFlash('danger', 'Vous ne participez pas à cette sortie');
            return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
        }
        /*
         * Verifier la date limite de debut
         */
        if($trip->getDateStart()<new \DateTime('now')){
            $this->addFlash('danger', 'La sortie à déjà démarrée');
            return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
        }
        /*
         * Verifier le status de la sortie
         */
        if($trip->getStatus()->getName() != 'Active' && $trip->getStatus()->getName() != 'Closure'){
            $this->addFlash('danger', 'Le status de la sortie ne permet pas la désinscription');
            return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
        }


        $inscription = $inscriptionRepository->findByParticipantAndTrip($this->getUser(), $trip);

        $entityManager->remove($inscription);
        $entityManager->flush();

        $this->addFlash('success', 'Vous n\'êtes plus inscrit à la sortie "' . $trip->getName() . '"');

        return $this->redirectToRoute('trip_detail_trip', ['id' => $trip->getId()]);
    }


    /**
     * ajax uploadInscriptionButton
     * @Route("/ChargerLesBouttonsInscriptions", name="ajax_upload_button_inscription")
     */
    public function ajaxUploadButtonInscription(Request $request, TripRepository $tripRepository,
                                                ParticipantRepository $participantRepository){
        $inscribed = false;

        $userId = $request->query->get('userId');
        $tripId = $request->query->get('tripId');

        $user = $participantRepository->findOneBy(['id'=>$userId]);
        $trip = $tripRepository->findOneBy(['id'=>$tripId]);

        foreach($trip->getInscription() as $inscription) {
            if($inscription->getParticipant() == $user){
                $inscribed = true;
                break;
            }
        }

        $places = true;
        if(sizeof($trip->getInscription()) >= $trip->getNbMaxRegistration()){
            $places = false;
        }

        return $this->render('inscription/ajaxUploadButton.html.twig', compact('inscribed', 'trip', 'places'));
    }

}

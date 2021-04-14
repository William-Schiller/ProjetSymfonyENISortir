<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Status;
use App\Entity\Trip;
use App\Form\CreateTripType;
use App\Form\UpdateManageTripType;
use App\Repository\AdressRepository;
use App\Repository\CityRepository;
use App\Repository\StatusRepository;
use App\Repository\TripRepository;
use App\Services\Trip\TripManagerServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TripManageController
 * @package App\Controller
 *
 * @Route(path="sortie/gestion/", name="tripManage_")
 */
class TripManageController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(TripRepository $tripRepository): Response
    {
//        $trips = $tripRepository->findBy(['promoter'=>$this->getUser()]);
        $trips = $tripRepository->findMyTripsNotInProgress($this->getUser());

        return $this->render('trip_manage/index.html.twig', compact('trips'));
    }

    /**
     * @Route(path="detail/{id}", requirements={"id":"\d+"}, name="update")
     */
    public function update(Request $request, TripRepository $tripRepository,
                           EntityManagerInterface $entityManager, TripManagerServices $managerServices){
        $id = $request->get('id');

        $trip = $tripRepository->findOneBy(['id' => $id]);

        if(is_null($trip) || $trip->getPromoter() != $this->getUser()){
            throw $this->createNotFoundException();
        }

        $trip->setDuration($trip->getDuration()/60/60); // Duration second on hours for form
        $form = $this->createForm(UpdateManageTripType::class, $trip);

        $form->handleRequest($request);
        $trip->setDuration($trip->getDuration()*60*60); // Duration hours on second

        if($form->isSubmitted() && $form->isValid() && $managerServices->checkDatesTrip($trip)){
            $trip->setPromoter($this->getUser());

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a bien été modifier'); //a afficher

            return $this->redirectToRoute('tripManage_index');//, ['id'=>$trip->getId()]);
            //TODO Return fiche detail de lulu
        }
        if(!$managerServices->checkDatesTrip($trip)){
            $this->addFlash('danger', 'La date de limite d\'inscription ne peut pas être suppérieur à la date de début');
        }

        $tripForm = $form->createView();

        return $this->render('trip_manage/detail.html.twig', compact('id', 'trip', 'tripForm'));
    }

    /**
     * @Route(path="ajouter", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager,
                           StatusRepository $statusRepository, TripManagerServices $managerServices){
        $trip = new Trip();
        $form = $this->createForm(CreateTripType::class, $trip);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $managerServices->checkDatesTrip($trip)){

            $trip->setDuration($trip->getDuration()*60*60); // Duration hour on second
            $trip->setStatus($statusRepository->findOneBy(['name' => 'Create']));
            $trip->setPromoter($this->getUser());

            $inscription = new Inscription();
            $inscription->setParticipant($this->getUser());
            $inscription->setTrip($trip);

            $entityManager->persist($trip);
            $entityManager->persist($inscription);
            $entityManager->flush();

            if(!empty($request->get('idPublish'))){
                return $this->redirectToRoute('tripManage_publish', ['id' => $trip->getId()]);
            }

            $this->addFlash('success', 'La sortie a bien été crée');

            return $this->redirectToRoute('tripManage_index');
        }
        if(!$managerServices->checkDatesTrip($trip)){
            $this->addFlash('danger', 'La date de limite d\'inscription ne peut pas être suppérieur à la date de début');
        }

        return $this->render('trip_manage/create.html.twig', ['tripForm' => $form->createView()]);
    }

    /**
     * @Route(path="publier/{id}", requirements={"id":"\d+"}, name="publish")
     */
    public function publish(Request $request, TripRepository $tripRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager){
        $id = $request->get('id');

        $trip = $tripRepository->findOneBy(['id' => $id]);

        if(is_null($trip) || $trip->getPromoter() != $this->getUser()){
            throw $this->createNotFoundException();
        }

        $status = $statusRepository->findOneBy(['name' => 'Active']);
        $trip->setStatus($status);

        $entityManager->persist($trip);
        $entityManager->flush();

        $this->addFlash('success', 'La sortie a bien été publiée'); //a afficher

        return $this->redirectToRoute('tripManage_index');
    }

    /**
     * @Route(path="supprimer/{id}", requirements={"id":"\d+"}, name="delete")
     */
    public function delete(Request $request, TripRepository $tripRepository, EntityManagerInterface $entityManager){
        $id = $request->get('id');

        $trip = $tripRepository->findOneBy(['id' => $id]);

        if(is_null($trip) || $trip->getPromoter() != $this->getUser() || $trip->getStatus()->getName() != 'Create'){
            throw $this->createNotFoundException();
        }

        $entityManager->remove($trip);
        $entityManager->flush();

        $this->addFlash('success', 'La sortie a bien été supprimée'); //a afficher

        return $this->redirectToRoute('tripManage_index');
    }

    /**
     * @Route(path="annuler/{id}", requirements={"id":"\d+"}, name="cancel")
     */
    public function cancel(Request $request, TripRepository $tripRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager){
        $id = $request->get('id');

        $trip = $tripRepository->findOneBy(['id' => $id]);

        if(is_null($trip) || $trip->getPromoter() != $this->getUser() || $trip->getStatus()->getName() != 'Active'){
            throw $this->createNotFoundException();
        }

        $status = $statusRepository->findOneBy(['name' => 'Desactivate']);
        $trip->setStatus($status);

        $entityManager->persist($trip);
        $entityManager->flush();

        $this->addFlash('success', 'La sortie a bien été annulée'); //a afficher

        return $this->redirectToRoute('tripManage_index');
    }

    /**
     * ajax city search
     * @Route("rechercheAdressesParVille", name="search_city")
     */
    public function searchCity(Request $request, AdressRepository $adressRepository) {

        $cityId = $request->query->get('keyword');
        $addresses = $adressRepository->searchByCityName($cityId);
        return $this->render('trip_manage/searchAddress.html.twig', compact('addresses'));
    }
}

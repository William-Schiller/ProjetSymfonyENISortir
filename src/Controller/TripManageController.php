<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\CreateTripType;
use App\Repository\StatusRepository;
use App\Repository\TripRepository;
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
        $trips = $tripRepository->findBy(['promoter'=>$this->getUser()]);

        return $this->render('trip_manage/index.html.twig', compact('trips'));
    }

//    /**
//     * @Route(path="detail/{id}", requirements={"id":"\d+"}, name="detail")
//     */
//    public function detail(Request $request, TripRepository $tripRepository){
//        $id = $request->get('id');
//
//        $trip = $tripRepository->findOneBy(['id' => $id]);
//
//        if(is_null($trip)){
//            throw $this->createNotFoundException();
//        }
//
//        return $this->render('trip/detail.html.twig', compact('id', 'trip'));
//    }

    /**
     * @Route(path="ajouter", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, TripRepository $tripRepository, StatusRepository $statusRepository){
        $trip = new Trip();
        $form = $this->createForm(CreateTripType::class, $trip);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $trip->setDuration($trip->getDuration()*60*60*24); // Duration day on second
            $trip->setStatus($statusRepository->findOneBy(['id'=>1]));
            $trip->setPromoter($this->getUser());

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a bien été crée'); //a afficher

            return $this->redirectToRoute('tripManage_index');
        }

        return $this->render('trip_manage/create.html.twig', ['tripForm' => $form->createView()]);
    }

}

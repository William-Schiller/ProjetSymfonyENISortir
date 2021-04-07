<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\CreateTripType;
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
    public function index(): Response
    {
        return $this->render('trip_manage/index.html.twig');
    }

    /**
     * @Route(path="ajouter", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, TripRepository $tripRepository){
        $trip = new Trip();
        $form = $this->createForm(CreateTripType::class, $trip);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //TODO

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a bien été crée'); //a afficher

            return $this->render('trip_manage/index.html.twig');
        }

        return $this->render('trip_manage/create.html.twig', ['tripForm' => $form->createView()]);
    }

}

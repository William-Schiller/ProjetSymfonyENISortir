<?php

namespace App\Controller;


use App\Repository\CampusRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TripController
 * @package App\Controller
 * @Route(path="sortie/", name="trip_")
 */
class TripController extends AbstractController
{

    /**
     * @Route("{numPage}", requirements={"numPage":"\d+"}, defaults={"numPage":"1"}, name="list", methods={"GET", "POST"})
     */
    public function list($numPage, Request $request, TripRepository $tripRepo, CampusRepository $campusRepo)
    {
        $nbLines = 10;
        $nbPages = $tripRepo->nbPages($nbLines);

        //début
        //Récupération de la liste des campus
        $campusList = $campusRepo->findAll();
        if (!empty($request->get('campusFilter'))) {
            $idCampus = $request->get('campusFilter');
            $trips = $tripRepo->findWhereCampusWithPage($nbLines, $numPage, $idCampus);
            return $this->render('trip/index.html.twig', compact('trips', 'numPage', 'nbPages', 'campusList', 'idCampus'));
        }
        //fin

        $trips = $tripRepo->findAllWithPage($nbLines, $numPage);
        return $this->render('trip/index.html.twig', compact('trips', 'numPage', 'nbPages', 'campusList'));

    }





    //Méthode findBy pour récupérer les sorties avec des critères de filtre et de tri

    /*$tripsCampus = $this->getDoctrine()->getRepository(Campus::class)
        ->findBy([], ['name' => 'desc']);
    $tripsName = $this->getDoctrine()->getRepository(Trip::class)
        ->findBy(['name']);
    $tripsDateStart = $this->getDoctrine()->getRepository(Trip::class)
        ->findBy(['dateStart']);
    $tripsLimitInscription = $this->getDoctrine()->getRepository(Trip::class)
        ->findBy(['dateLimitInscription']);

    return $this->render('trip/index.html.twig', [
        'tripsCampus' => $tripsCampus,
        'tripsName' => $tripsName,
        'tripsDateStart' => $tripsDateStart,
        'dateLimitInscription' => $tripsLimitInscription
    ]);*/


    //Liste des campus
    //$repository = $this->getDoctrine()->getRepository(Campus::class);
    //$campus = $repository->findAll();

    //return $this->render('trip/index.html.twig', array('campus' => $campus));


    //Filtrer la séléction avec une recherche de mot
    /*/**
     * @Route("/recherche", name="search", methods={"GET", "POST"})
     */
    /* public function search(Request $request)
     {
         $form = $this->createFormBuilder()->add('recherche', SearchType::class)->getForm();

         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $data = $form->getData();
             $em = $this->container->get('doctrine')->getManager();
             $trips = $em->getRepository('App\Entity\Trip')->search($data ['recherche']);
             return $this->render('trip/index.html.twig', [
                 'trip' => $trips
             ]);
         }
         return $this->render('trip/index.html.twig', [
             'form' => $form->createView()
         ]);
     }*/

    //Pagination


    //Récupération des sorties publiées sur chaque campus
    //Utilisateur inscrit ou utilisateur organisateur
    //Filtrer la liste en fonction du campus, du nom de la sortie, de la date de début et de fin
    //Filtre avec checkbox : organisateur/rice, inscrit/e, pas inscrit/e, sortie terminée


}

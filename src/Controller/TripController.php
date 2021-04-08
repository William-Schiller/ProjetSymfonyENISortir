<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TripController
 * @package App\Controller
 * @Route(path="sortie/", name="trip_")
 */
class TripController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET","POST"})
     */
    public function list(EntityManagerInterface $entityManager)
    {
        //TODO Afficher la liste des sorties

        //Récupération des entités dans la BDD
        $trip = $entityManager->getRepository('App:Trip')->findAll();

        //Lien avec le dossier trip + index.html.twig
        return $this->render('trip/index.html.twig', ['trips' => $trip]);


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


    }


    //Récupération des sorties publiées sur chaque campus
    //Utilisateur inscrit ou utilisateur organisateur
    //Filtrer la liste en fonction du campus, du nom de la sortie, de la date de début et de fin
    //Filtre avec checkbox : organisateur/rice, inscrit/e, pas inscrit/e, sortie terminée


}

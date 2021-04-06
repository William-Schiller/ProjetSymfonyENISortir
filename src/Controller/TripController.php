<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TripController
 * @package App\Controller
 * @Route(path="sortie/", name="trip_")
 */
class TripController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(): Response
    {
        //TODO Afficher la liste des sorties
        return $this->render('trip/index.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }

    /**
     * @Route(path="gestion/", name="homeManage"
     */
    public function homeManage(){
        //TODO menu gestion des sorties
    }

    /**
     * @Route(path="gestion/ajouter", name="create"
     */
    public function create(){
        //TODO creation d'une sortie
    }
}

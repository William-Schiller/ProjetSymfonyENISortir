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
     *
     * @Route("", name="list", methods={"GET/POST"})
     */
    public function list(EntityManagerInterface $entityManager)
    {
        //TODO Afficher la liste des sorties

        //Récupération des entités dans la BDD
        $trip = $entityManager->getRepository('trip:index');

        //Lien avec le dossier trip + index.html.twig
        return $this->render('trip/index.html.twig', ['trip' => $trip]);
    }


}

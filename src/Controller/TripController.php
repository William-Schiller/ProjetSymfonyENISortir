<?php

namespace App\Controller;


use App\Data\SearchData;
use App\Entity\Campus;
use App\Form\SearchType;
use App\Repository\CampusRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
    public function list($numPage, Request $request, TripRepository $tripRepo )
    {
        $nbLines = 10;
        $nbPages = $tripRepo->nbPages($nbLines);

        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $formSearch = $form->createView();


        $trips = $tripRepo->findSearch($data, $this->getUser()->getId());

        return $this->render('trip/index.html.twig', compact('trips', 'numPage',
            'nbPages', 'formSearch'));

    }
}



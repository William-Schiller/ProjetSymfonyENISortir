<?php

namespace App\Controller;


use App\Data\SearchData;
use App\Entity\Campus;
use App\Entity\Inscription;
use App\Form\SearchType;
use App\Repository\CampusRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function list($numPage, Request $request, TripRepository $tripRepo, PaginatorInterface $paginator)
    {
        $nbLines = 10;
        $nbPages = $tripRepo->nbPages($nbLines);

        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $formSearch = $form->createView();

        $event = $tripRepo->findSearch($data, $this->getUser()->getId());

        $trips = $paginator
            ->paginate($event, $request->query
                ->getInt('page',1),
                10
            );

        return $this->render('trip/index.html.twig', compact('trips', 'numPage',
            'nbPages', 'formSearch'));

    }

    /**
     * @Route(path="detailSortie/{id}", requirements={"id":"\d+"}, name="detail_trip")
     */
    public function detailTrip(Request $request, TripRepository $tripRepository)
    {
        $id = $request->get('id');
        $trip = $tripRepository->findOneBy(['id' => $id]);

        return $this->render('trip/detail.html.twig', compact('trip'));
    }
}



<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Participant;
use App\Form\AddressType;
use App\Form\CityType;
use App\Form\UpdateManagerCityType;
use App\Form\UpdateManageTripType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Proxies\__CG__\App\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddressController
 * @package App\Controller
 *
 * @Route("ville", name="city_")
 */
class CityController extends AbstractController
{

    /**
     * @Route("/ajouter", name="list")
     */
    public function list(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator)
    {
        $city = $entityManager->getRepository('App:City')->findAll();

        $cities = $paginator
            ->paginate($city, $request->query
                ->getInt('page',1),
                10
            );

        return $this->render('city/createCity.html.twig', [
            'cities'=>$cities,
        ]);
    }

    /**
     * @Route("/ajouter", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $createCity = "";
        if (!empty($request->get('createCity'))) {
            $createCity = $request->get('createCity');
        }

        $city = new city();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', 'le campus a bien été enregistré'); //a afficher

            if (!empty($createCity)) {
                return $this->redirectToRoute('city_create');
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('city/createCity.html.twig', ['cityForm' => $form->createView(), 'createCity' => $createCity]);
    }

    /**
     * @Route("/modifier/{id}" , requirements={"id":"\d+"}, name="modify")
     */
    public function modify(Request $request,CityRepository $cityRepository, EntityManagerInterface $entityManager, $id)
    {
        $city = $this->getDoctrine()
            ->getRepository('App:City')
            ->find($id);

        if(!$city) {
            throw $this->createNotFoundException();
        }
        $cityForm = $this->createForm(CityType::class, $city);

        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();
            $this->addFlash(
                'success', 'La ville a bien été modifiée');
        }
        return $this->render('city/detailCity.html.twig',
            ['cityForm'=>$cityForm->createView()]);
    }

    /**
     * @Route("/retirer/{id}", requirements={"id":"\d+"}, name="delete")
     */
    public function delete( EntityManagerInterface $em, $id)
    {
        $city = $this->getDoctrine()
            ->getRepository('App:City')
            ->find($id);


        if(is_null($city)){
            throw $this->createNotFoundException();
        }

        $em->remove($city);
        $em->flush();

        $this->addFlash('success', 'La ville a bien été supprimée'); //a afficher

        return $this->redirectToRoute('city_create');

    }

}

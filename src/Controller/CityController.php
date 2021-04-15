<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddressController
 * @package App\Controller
 *
 * @Route("admin/ville", name="city_")
 */
class CityController extends AbstractController
{

    /**
     * @Route("", name="list")
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
    public function create(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $cityList = $em->getRepository('App:City')->findAll();

        $cities = $paginator
            ->paginate($cityList, $request->query
                ->getInt('page',1),
                10
            );
        $city = new city();
        $cityForm = $this->createForm(CityType::class, $city);
        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $em->persist($city);
            $em->flush();

            if (!empty($city)) {
                return $this->redirectToRoute('city_create');
            }
            $this->addFlash('success', 'la ville a bien été enregistré');

            return $this->redirectToRoute('city_create');
        }

        return $this->render('city/createCity.html.twig',
            ['cityForm'=>$cityForm->createView(), 'cities'=>$cities]);
    }

    /**
     * @Route("/modifier/{id}" , requirements={"id":"\d+"}, name="modify")
     */
    public function modify(Request $request, $id)
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

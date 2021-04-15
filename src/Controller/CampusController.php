<?php

namespace App\Controller;

use App\Data\SearchDataCampus;
use App\Entity\Campus;
use App\Form\CampusType;
use App\Form\FiltreCampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddressController
 * @package App\Controller
 *
 * @Route("admin/campus", name="campus_")
 */
class CampusController extends AbstractController
{

    /**
     * @Route("", name="create")
     */
    public function create(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, CampusRepository $campusRepository)
    {
        $campusList = $em->getRepository('App:Campus')->findAll();

        $campuS = $paginator
            ->paginate($campusList, $request->query
                ->getInt('page', 1),
                10
            );

        // Insertion d'une nouvelle ville
        $campus = new campus();
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $em->persist($campus);
            $em->flush();

            if (!empty($campus)) {
                return $this->redirectToRoute('campus_create');
            }
            $this->addFlash('success', 'la campus a bien été enregistré');

            return $this->redirectToRoute('campus_create');
        }

        //filtre
        $data = new SearchDataCampus();
        $form = $this->createForm(FiltreCampusType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchCampus = $campusRepository->findSearch($data);
            $campuS= $paginator
                ->paginate($searchCampus, $request->query
                    ->getInt('page', 1),
                    10
                );

        }
        return $this->render('campus/createCampus.html.twig',

            ['campusForm' => $campusForm->createView(),
                'campus' => $campuS,
                'formSearch' => $form->createView(),
            ]);
    }

    /**
     * @Route("/modifier/{id}" , requirements={"id":"\d+"}, name="modify")
     */
    public function modify(Request $request, $id)
    {
        $campus = $this->getDoctrine()
            ->getRepository('App:Campus')
            ->find($id);

        if(!$campus) {
            throw $this->createNotFoundException();
        }
        $campusForm = $this->createForm(CampusType::class, $campus);

        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($campus);
            $em->flush();
            $this->addFlash(
                'success', 'La campus a bien été modifié');
        }
        return $this->render('campus/detailCampus.html.twig',
            ['campusForm'=>$campusForm->createView()]);
    }

    /**
     * @Route("/retirer/{id}", requirements={"id":"\d+"}, name="delete")
     */
    public function delete( EntityManagerInterface $em, $id)
    {
        $campus = $this->getDoctrine()
            ->getRepository('App:Campus')
            ->find($id);


        if(is_null($campus)){
            throw $this->createNotFoundException();
        }

        $em->remove($campus);
        $em->flush();

        $this->addFlash('success', 'La ville a bien été supprimée'); //a afficher

        return $this->redirectToRoute('campus_create');

    }

}

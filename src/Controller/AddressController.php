<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddressController
 * @package App\Controller
 *
 * @Route("adresse", name="address_")
 */
class AddressController extends AbstractController
{
    /**
     * @Route("/ajouter", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $createTrip = "";
        if(!empty($request->get('createTrip'))){
            $createTrip = $request->get('createTrip');
        }

        $address = new Adress();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'l\'adresse a bien été enregistré'); //a afficher

            if(!empty($createTrip)){
                return $this->redirectToRoute('tripManage_create');
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('address/create.html.twig', ['addressForm' => $form->createView(), 'createTrip' => $createTrip]);
    }
}

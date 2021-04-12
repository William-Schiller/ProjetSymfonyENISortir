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
 * @Route("adresse", name="address")
 */
class AddressController extends AbstractController
{
    /**
     * @Route("/ajouter", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = new Adress();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'l\'adresse a bien été enregistré'); //a afficher

            return $this->redirectToRoute('home');
        }

        return $this->render('address/create.html.twig', ['addressForm' => $form->createView()]);
    }
}

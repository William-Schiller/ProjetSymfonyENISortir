<?php

namespace App\Controller;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil", name="profilePaticipant_")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/detailParticipant/{id}", requirements={"id": "\d+"}, name="showParticipant_", methods={"GET"})
     */
    public function showParticipant(Request $request, EntityManagerInterface $entityManager)
    {
        // Récupération du paramètre GET (PATH) dans la requête
        $id = $request->get('id');
        // Récupération de l'entité dans la BDD
        $participant = $entityManager->getRepository(Participant::class)->find($id);

        if (is_null($participant)) {
            throw $this->createNotFoundException('Aucun utilisateur ne correspond à l\'id :' . $id);
        }
        return $this->render('participant/profileParticipant.html.twig', ['participant'=> $participant]);
    }
}


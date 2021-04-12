<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use App\Repository\TripRepository;
use App\Services\Inscription\InscriptionServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InscriptionController
 * @package App\Controller
 * @Route("inscription", name="inscription_")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/s'inscrire/{id}", requirements={"id":"\d+"}, name="register")
     */
    public function register($id, EntityManagerInterface $entityManager, TripRepository $tripRepository,
                        InscriptionRepository $inscriptionRepository, InscriptionServices $inscriptionServices)
    {

        /*
         *Code a copier :
         * Dans index.html de Trip :
         *
                        <td>{{ "Afficher" }}
                        {# TODO SUPPRIMER #}
                        {% if app.user != trip.promoter %}
                            <a href="{{ path('inscription_register', {'id': trip.id }) }}">S'inscrire</a>
                        {% endif %}
                        {# TODO JUSQUE LA #}
                    </td>
                </tr>
            {% endfor %}
         *
         * Code original
         *          <td>{{ trip.promoter }}</td>
         *       <td>{{ "Afficher" }}</td>
         *  </tr>
         *{% endfor %}
         */

        $trip = $tripRepository->findOneBy(['id' => $id]);

        /*
         * Verifier que l'ustilisateur ne soit pas inscrit ou qu'il ne soit pas l'organisteur
         */
        if($this->getUser() == $trip->getPromoter() || !empty($inscriptionRepository->findByParticipantAndTrip($this->getUser(), $trip))){
            $this->addFlash('danger', 'Vous ne pouvez pas vous inscrire plus d\'une fois à une sortie');
            return $this->render('home/index.html.twig'); //TODO changer route
        }
        /*
         * Verifier la date limite d'inscription
         */
        if($trip->getDateLimitInscription()<new \DateTime('now')){
            $this->addFlash('danger', 'La date limite d\'inscription à cette sortie est dépassée');
            return $this->render('home/index.html.twig'); //TODO changer route
        }
        /*
         * Verifier le status de la sortie
         */
        if($trip->getStatus()->getName() != 'Active' ){
            $this->addFlash('danger', 'Le status de la sortie ne permet pas l\'inscription');
            return $this->render('home/index.html.twig'); //TODO changer route
        }
        /*
         * Verifier qu'il reste de la place
         */
        if(!$inscriptionServices->checkPlaces($trip, $inscriptionRepository)){
            $this->addFlash('danger', 'Impossible : le nombre maximum de participants est atteint');
            return $this->redirectToRoute('trip_list');
        }

        $inscription = new Inscription();

        $inscription->setParticipant($this->getUser());
        $inscription->setTrip($trip);

        $entityManager->persist($inscription);
        $entityManager->flush();

        $this->addFlash('success', 'Vous êtes inscrit à la sortie "' . $trip->getName() . '"');

        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]); //TODO Une bonne route
    }

    /**
     * @Route("/rechercherSiInscris", name="searchIfInscripted")
     */
    public function searchIfInscripted(Request $request, InscriptionRepository $inscriptionRepository, TripRepository $tripRepository) {
    //TODO MARCHE PAS DU TOUT
//        $check = false;
//
//        $trip = $tripRepository->findOneBy(['id' => $idTrip]);
//        $inscription = $inscriptionRepository->findByParticipantAndTrip($this->getUser(), $trip);
//
//        if(!empty($inscription)){
//            $check = true;
//        }
//        return $check;
    }

}

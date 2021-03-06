<?php


namespace App\Controller;


use App\Entity\Participant;
use App\Repository\StatusRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/participant/{numPage}", requirements={"numPage":"\d+"}, defaults={"numPage":"1"}, name="listUser")
     */
    public function listUser($numPage, Request $request, EntityManagerInterface $entityManager){
        $nbLines=10;
        $userRepo = $this->getDoctrine()->getRepository(Participant::class);
        $nbPages = $userRepo->nbPages($nbLines);
        $users = $userRepo->findUser($nbLines, $numPage);

        return $this->render('admin/manageUser.html.twig', [
            'users'=>$users,
            'numPage'=>$numPage,
            'nbPages'=>$nbPages,
            ]);
    }

    /**
     * @Route("/activation/{id}", requirements={"id" = "\d+"}, name="activation")
     */
    public function activate($id, Request $request, EntityManagerInterface $em){
        if(!empty($request->get("active"))){
            $active = $request->get("active");

        $userRepo = $this->getDoctrine()->getRepository(Participant::class);
        $user = $userRepo->findOneBy(['id'=>$id]);

        $rolesAdmin = ['ROLE_ADMIN', 'ROLE_USER'];
        $rolesUser = ['ROLE_USER'];
        if($active == 'activate'){
            $user->setActive(1);
            if($user->getAdmin() == 1){
                $user->setRoles($rolesAdmin);
            } else { //ajout
                $user->setRoles($rolesUser);
            } //ajout
        } else if($active == 'deactivate') {
            $user->setActive(0);
            $user->setRoles([]);
        }

        $em->persist($user);
        $em->flush();
        }

        return $this->redirectToRoute('admin_listUser');
    }

    /**
     * @Route("annulerSortie/{id}", requirements={"id" = "\d+"}, name="cancel_trip")
     */
    public function cancelTrip(Request $request, TripRepository $tripRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager){
        $id = $request->get('id');

        $trip = $tripRepository->findOneBy(['id' => $id]);

        if(is_null($trip) || !$this->getUser()->getAdmin() || $trip->getStatus()->getName() != 'Active'){
            throw $this->createNotFoundException();
        }

        $cancelInformationText = "Sortie annul?? par un administrateur "  . ' [' . $trip->getInformationTrip() . ']';

        $status = $statusRepository->findOneBy(['name' => 'Desactivate']);
        $trip->setStatus($status);
        $trip->setInformationTrip($cancelInformationText);

        $entityManager->persist($trip);
        $entityManager->flush();

        $this->addFlash('success', 'La sortie a bien ??t?? annul??e');

        return $this->redirectToRoute('trip_list');
    }

}
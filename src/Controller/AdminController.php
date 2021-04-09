<?php


namespace App\Controller;


use App\Entity\Participant;
use App\Repository\ParticipantRepository;
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

        if($active == 'activate'){
            $user->setActive(1);
        } else if($active == 'deactivate') {
            $user->setActive(0);
        }

        $em->persist($user);
        $em->flush();
        }

        return $this->redirectToRoute('admin_listUser');
    }



}
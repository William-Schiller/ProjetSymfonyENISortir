<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\EditUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class EditUserController extends AbstractController
{
    /**
     * @Route("/showUser/{id}", name="showUser", requirements={"id"="\d+"})
     */
    public function showUser($id)
    {
        //methode qui retourne un objet user qui sera passé en parametre
        $user = $this->getDoctrine()
            ->getRepository('App:Participant') //où se situe l'entité concerné
            ->find($id);// accepte en para un élément de l'idée à récupérer
        if(!$user){
        throw $this ->createNotFoundException('Aucun utilisateur correspond à l\'id :' .$id );
        }
        $userForm =$this->createForm(EditUserType::class, $user);


        return $this->render('registration/editUser.html.twig', ['userForm'=>$userForm->createView()]);

    }

  //  public function updatePicture(){
        //if($form->isSubmitted() && $form->isValid()){
          //  $image = $userForm->get('picture')->getData(); //Récuperer le fichier
    //    }
    //}
        //Recuperation du param GET (PATH) dans la requete
        // $id = $request->get('id');
        // $participant = $entityManager->getRepository('App:Participant')->getParticipant($id);
        //Verrif interne
        // if(is_null($participant)){
        //   throw $this->createNotFoundException();
        //}
        //passage a la vue
        //return $this->render('registration/editUser.html.twig', ['participant'=>$participant]);
   // }
}

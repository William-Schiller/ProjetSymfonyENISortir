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
     * @Route("/Utilisateur/{id}", name="showUser", requirements={"id"="\d+"})
     */
    public function showUser(Request $request, $id)
    {
        //methode qui retourne un objet user qui sera passé en parametre
        $user = $this->getDoctrine()
            ->getRepository('App:Participant') //où se situe l'entité concerné
            ->find($id);// accepte en para un élément de l'idée à récupérer

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur correspond à l\'id :' . $id);
        }

        //Instanciation du formulaire basé sur un objet user
        $userForm = $this->createForm(EditUserType::class, $user);

        //determine si le formulaire a ete soumis ou non(si soumis, il ecrit les
        //valeurs saisies dans les propritetes de l'objet lié au formulaire
        $userForm->handleRequest($request);

        //controle des données soumises
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $image = $userForm->get('picture')->getData(); //Récuperer le fichier

            //stocker le nom du fichier dans une variable
            if($image){
                //Recuperer le nom du fichier
                $originalFileName=pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                //générer un nouveau nom unique pour l'image
                $newFileName = uniqid().'.'.$image->guessExtension();

                    //Charger l'image dans le progjet 'picture_profile_directory' qui est ensuite configue dans service.yaml
                try {//Upload l'image dans un fichier du projet
                    $image->move(
                        $this->getParameter('picture_profile_directory'), $newFileName);
                }catch(FileException $e) {
                    //TODO traiter l'exeption
                }
                $user->setPictureFileName($newFileName); //Upload le user avec le nom du fichier
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                "Les modifications ont bien été enregistrées");
        }
        return $this->render('registration/editUser.html.twig', ['userForm' => $userForm->createView()]);

    }

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

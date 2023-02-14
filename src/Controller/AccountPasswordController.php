<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountPasswordController extends AbstractController
{
    #[Route('/compte/password', name: 'account_password')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $encoder): Response
    {   
        $notification = null;
        
        // on récupère l'utilisateur connecté avec la méthode getUser()
        $user = $this->getUser();        
        // on crée le formulaire avec la méthode createForm() et on lui passe en paramètre le type de formulaire crée et l'objet $user
        $form = $this->createForm(ChangePasswordType::class, $user);
        // on récupère les données du formulaire avec la méthode handleRequest()
        $form->handleRequest($request);            
        // on vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();        
            // on vérifie si le mot de passe saisi correspond au mot de passe de l'utilisateur
            if ($encoder->isPasswordValid($user, $old_password)) {               
                // on récupère le nouveau mot de passe
                $new_password = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_password);
                // on met à jour le mot de passe de l'utilisateur
                $user->setPassword($password);
                // on enregistre l'utilisateur en base de données
                $entityManagerInterface->persist($user);
                // on exécute la requête SQL d'insertion
                $entityManagerInterface->flush();                
                // // on ajoute un message flash
                //  $this->addFlash('success', 'Votre mot de passe a bien été modifié !');
                $notification = "Votre mot de passe a bien été modifié !";
            }else{
                // // on ajoute un message flash
                // $this->addFlash('danger', 'Votre mot de passe actuel n\'est pas correct !');
                $notification = "Votre mot de passe actuel n'est pas correct !";
            }
        }     
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
        
    }
}

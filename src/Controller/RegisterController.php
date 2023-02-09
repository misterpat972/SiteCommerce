<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function index( Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManagerInterface ): Response
    {
        // on instancie un nouvel objet User pour pouvoir l'utiliser dans le formulaire
        $user = new User();

        // on instancie le formulaire en lui passant l'objet User
        $form = $this->createForm(RegisterType::class, $user);
        // on demande au formulaire de gérer la requête HTTP avec la méthode handleRequest()
        $form->handleRequest($request);

        // on vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère les données du formulaire
            $user = $form->getData();
            // on hash le mot de passe
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $user,
                    $user->getPassword()
                )
            ); 
            
            // on enregistre l'utilisateur en base de données
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();            

            // on ajoute un message flash
            $this->addFlash('success', 'Votre compte a bien été créé !');

            // on redirige l'utilisateur vers la page de connexion
            return $this->redirectToRoute('home');
        }        
        
        return $this->render('register/inscription.html.twig', [
            // on passe le formulaire à la vue pour pouvoir l'afficher dans le template
            'form' => $form->createView(),
        ]);
    }
}

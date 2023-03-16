<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
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
        
        
         // creation d'une variable message flash
         $message_flash = [
            'warning' => 'L\'adresse email existe déjà !',
            'success' => 'Votre compte a bien été créé !',
        ];

        
        
        // on vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // on verifie si l'utilisateur existe déjà en base de données
            $search_email = $entityManagerInterface->getRepository(User::class)->findOneByEmail($user->getEmail());
           

           // si l'utilisateur existe déjà, on affiche un message flash et on redirige l'utilisateur vers la page d'inscription
            if($search_email) {
                // on ajoute un message flash
                $this->addFlash("warning", $message_flash['warning']);
                // $this->addFlash('warning', 'L\'adresse email existe déjà !');
                return $this->redirectToRoute('inscription');
            }else{
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
        // on exécute la requête SQL d'insertion
        $entityManagerInterface->flush();
        // on envoie un email de confirmation d'inscription
        $content = "Bonjour ".$user->getFirstname()."<br/>Bienvenue sur la boutique de la boutique.<br/><br/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nisl nisl";
        $mail = new Mail();
        $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur la boutique commerce', $content);        
     
        // // on ajoute un message flash
        $this->addFlash("success", $message_flash['success']);
        // $this->addFlash('success', 'Votre compte a bien été créé !');
        // on redirige l'utilisateur vers la page de connexion
        return $this->redirectToRoute('app_login');
        }
    }        
        
        return $this->render('register/inscription.html.twig', [
            // on passe le formulaire à la vue pour pouvoir l'afficher dans le template
            'form' => $form->createView(),
            // on passe à la vue les mesages flash qui ont été ajoutés
            'message_flash' => $message_flash,


        ]);
    }
}

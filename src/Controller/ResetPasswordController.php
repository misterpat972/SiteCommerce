<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {   
        // si l'utilisateur est connecté, on le redirige vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }   
        // si le formulaire est soumis et que l'email est valide   
        if($request->get('email')){
            // on récupère l'utilisateur correspondant à l'email    
         $user =  $entityManagerInterface->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);
         // si l'utilisateur existe
         if($user){
            // on crée un objet ResetPassword pour enregistrer en base de données la demande de réinitialisation de mot de passe avec (user, token, createdAt) de la table reset_password
            $reset_password = new ResetPassword();
            // on lui associe l'utilisateur correspondant
            $reset_password->setUser($user);
            // on génère un token unique  
            $reset_password->setToken(uniqid());
            // on définit la date de création
            $reset_password->setCreatedAt(new DateTimeImmutable());
            // on enregistre en base de données
            $entityManagerInterface->persist($reset_password);
            $entityManagerInterface->flush();

            // // on génère un lien de réinitialisation de mot de passe avec le token généré
            // $url = $this->generateUrl('update_password', ['token' => $reset_password->getToken()]);
            // // on génère le contenu du mail avec le lien de réinitialisation de mot de passe
            // $content = "Bonjour ".$user->getFirstname()."<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site La Boutique Française.<br/><br/>";
            // // on ajoute le lien de réinitialisation de mot de passe dans le contenu du mail
            // $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe</a>.";
            // // on envoie un email à l'utilisateur avec un lien de réinitialisation de mot de passe
            // $mail = new Mail();
            // $mail->send($user->getEmail(), $user->getFirstname().''.$user->getLastname(), 'Réinitialisation de votre mot de passe', $content );
            // $this->addFlash('notice', 'Vous allez recevoir un email pour réinitialiser votre mot de passe');
            // on redirige l'utilisateur vers la page de connexion
           
            // on redige l'utilisateur vers la page de réinitialisation de mot de passe avec le token
            return $this->redirectToRoute('update_password', ['token' => $reset_password->getToken()]);
           
         }else{
            $this->addFlash('notice', 'Cette adresse email est inconnue');
         }
         
        }
        

        return $this->render('reset_password/resetpassword.html.twig');
    }
    
    #[Route('/modfiier-mon-mot-de-passe/{token}', name: 'update_password')]
        public function update( $token, EntityManagerInterface $entityManagerInterface, Request $request,  UserPasswordHasherInterface $encoder)
        {
            $reset_password = $entityManagerInterface->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

            if(!$reset_password){
                return $this->redirectToRoute('reset_password');
            }
            // vérifier si le createdAt = now - 3h
            $now = new DateTimeImmutable();
            if($now > $reset_password->getCreatedAt()->modify('+ 3 hour')){
                $this->addFlash('notice', 'Votre demande de mot de passe a expiré, merci de la renouveler');
                return $this->redirectToRoute('reset_password');
            }

            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // on récupère le nouveau mot de passe                 
                $new_pwd = $form->get('new_password')->getData();                
                // encodage du mot de passe
                 $password =  $encoder->hashPassword($reset_password->getUser(), $new_pwd);
                // on modifie le mot de passe de l'utilisateur
                $reset_password->getUser()->setPassword($password);
                // on enregistre en base de données
                $entityManagerInterface->flush();
                // on envoi un message flash
                $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour');
                // on redirige l'utilisateur vers la page de connexion
                return $this->redirectToRoute('app_login');                
                
            }
           
            return $this->render('reset_password/update.html.twig', [
                'form' => $form->createView()
            ]);
        }
}

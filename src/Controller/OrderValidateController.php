<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderValidateController extends AbstractController
{
    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index($stripeSessionId, EntityManagerInterface $entityManagerInterface, Cart $cart): Response
    {
        $order = $entityManagerInterface->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
       
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        } 

        // modifier le statut isPaid de la commande en true
        if(!$order->getState() == 0){
            // si la commande est payé on vide le panier
            $cart->remove();
            // modifier le statut isPaid de la commande en true
            $order->setState(1);
            // Je flush en base de données 
            $entityManagerInterface->flush();
            // on envoie un email de confirmation d'inscription
            $content = "Bonjour ".$order->getUser()->getFirstname()." ".$order->getUser()->getLastname().", <br/>Merci pour votre commande. <br/>Vous allez recevoir un email de confirmation de paiement.";
            $mail = new Mail();
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande sur la boutique commerce est validé', $content);        
     
        }

        return $this->render('order_validate/order_validate.html.twig',[
            'order' => $order
        ]
        );
    }
}

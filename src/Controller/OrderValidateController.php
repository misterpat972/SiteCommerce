<?php

namespace App\Controller;

use App\Classe\Cart;
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
        if(!$order->isIsPaid()){

            // si la commande est payé on vide le panier
            $cart->remove();

            // modifier le statut isPaid de la commande en true
            $order->setIsPaid(1);
            // Je flush en base de données 
            $entityManagerInterface->flush();
        }


        return $this->render('order_validate/order_validate.html.twig',[
            'order' => $order
        ]
        );
    }
}

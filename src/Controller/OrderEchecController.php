<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderEchecController extends AbstractController
{
    #[Route('/commande/echec/{stripeSessionId}', name: 'order_echec')]
    public function index($stripeSessionId,  EntityManagerInterface $entityManagerInterface ): Response
    {
        $order = $entityManagerInterface->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        
        // envoyer un mail à l'utilisateur pour lui dire que sa commande a échoué



        return $this->render('order_echec/order_echec.html.twig',[
            'order' => $order
        ]);
    }
}

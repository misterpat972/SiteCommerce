<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    #[Route('/account/mes-commandes', name: 'account_order')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {   
       
        $orders = $entityManagerInterface->getRepository(Order::class)->findSuccessOrders($this->getUser());
        
        
        return $this->render('account/commande.html.twig', [
            "orders" => $orders,
        ]);
    }


    #[Route('/account/mes-commandes/{reference}', name: 'account_order_show')]
    public function show($reference, EntityManagerInterface $entityManagerInterface): Response
    {  
        // on récupère la commande grâce à la référence  
       $order = $entityManagerInterface->getRepository(Order::class)->findOneByReference($reference);                                                                                                          
       
       // Si la commande n'existe pas ou si elle ne correspond pas à l'utilisateur connecté alors on le redirige vers la page mes commandes
         if(!$order || $order->getUser() != $this->getUser()){
              return $this->redirectToRoute('account_order');
            }

        return $this->render('account/order_show.html.twig', [
            "order" => $order,
        ]);
           
    }
}

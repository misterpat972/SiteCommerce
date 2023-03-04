<?php

namespace App\Controller;

use App\Form\OrderType;
use DOTNET;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'order')]
    public function index(): Response
    {
       $form = $this->createForm(OrderType::class, null, [
        'user'=> $this->getUser(),        
       ]);
               
        return $this->render('order/commande.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

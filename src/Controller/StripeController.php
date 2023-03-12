<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'stripe_create_session')]
    public function index( Cart $cart )
    {   

        // je creer un tableau pour définir les données de mon produit stripe
        $product_for_stripe = [];    
        // je définis l'url de redirection en cas de succès 
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        // je récupère le contenu du panier 
        foreach($cart->getFull() as $product){

            $product_for_stripe = [
                // je définis les données du produit
                'price_data' => [
                // je définis la devise
                'currency' => 'eur',
                 
                // je définis les datas du produit que je souhaite enregistrer
                'product_data' => [
                 // je définis le nom du produit
                 'name' => $product['product']->getName(),
                  // je définis l'image du produit'
                 'images' => [$YOUR_DOMAIN."/uploads"."/".$product['product']->getIllustration()],
               
                ],
                // je définis le prix du produit
                'unit_amount' => $product['product']->getPrice() * 100,
              ],
              // je définis la quantité du produit
              'quantity' => $product['quantity'],
            ];

        }

        // integration de stripe clé api
        Stripe::setApikey('sk_test_51Mk7OXGR96Yhx7AaULpIVLff0IN5jxP3f3BinbfA8n1bIx4uKfMlBCyhxJaetnTNDFM4KNhMq392WshpjKmhelQn00yvE4H46H');
                   

        // je crée une session de paiement stripe   
        $checkout_session = Session::create([                     
           
            // je définis mes méthodes de paiement 
            'payment_method_types' => ['card'],
            // line_items correspond aux produits que je souhaite acheter 
            'line_items' => [[
              $product_for_stripe
            ]],
            // // je définis le mode de paiement
            'mode' => 'payment',
            // je définis l'url de redirection en cas de succès
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            // 'success_url' => 'http://127.0.0.1:8000/commande/merci/{CHECKOUT_SESSION_ID}',
            // je définis l'url de redirection en cas d'échec
            'cancel_url' => $YOUR_DOMAIN . '/commande/echec/{CHECKOUT_SESSION_ID}',
          ]);   
          
          // je retourne la réponse de stripe en json avec le JSONResponse de symfony 

          $response = new JsonResponse(['id' => $checkout_session->id]);
          // je fais un return de la response
          return $response;
        
    }
}



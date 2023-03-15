<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]

    public function index( Cart $cart, $reference, EntityManagerInterface $entityManager )
    {   

        // je creer un tableau pour définir les données de mon produit stripe
        $product_for_stripe = [];    
        // je définis l'url de redirection en cas de succès 
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        // je récupère la commande en fonction de la référence
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if(!$order){
            new JsonResponse(['error' => 'order']);
        }

        // je récupère le contenu du panier 
        foreach($order->getOrderDetails() as $product){
         $product_objet = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                // je définis les données du produit
                'price_data' => [
                // je définis la devise
                'currency' => 'eur',
                 // je définis le prix du produit et je le multiplie par 100 pour stripe
                'unit_amount' => $product->getPrice() * 100,

                // je définis les datas du produit que je souhaite enregistrer
                'product_data' => [
                 // je définis le nom du produit
                 'name' => $product->getProduct(),
                  // je définis l'image du produit'
                 'images' => [$YOUR_DOMAIN."/uploads"."/".$product_objet->getIllustration()],
               
                ],
                
              ],
              // je définis la quantité du produit
              'quantity' => $product->getQuantity(),
            ];

        }

        // je récupère le transporteur de la commande pour le définir dans stripe
        $product_for_stripe[] = [
          // je définis les données du produit
          'price_data' => [
          // je définis la devise
          'currency' => 'eur',
           // je définis le prix du produit et je le multiplie par 100 pour stripe
          'unit_amount' => $order->getCarrierPrice(),

          // je définis les datas du produit que je souhaite enregistrer
          'product_data' => [
           // je définis le nom du produit
           'name' => $order->getCarrierName(),
            // je définis l'image du produit'
           'images' => [$YOUR_DOMAIN],
         
          ],
          
        ],
        // je définis la quantité du produit
        'quantity' => 1,
      ];


        // integration de stripe clé api
        Stripe::setApikey('sk_test_51Mk7OXGR96Yhx7AaULpIVLff0IN5jxP3f3BinbfA8n1bIx4uKfMlBCyhxJaetnTNDFM4KNhMq392WshpjKmhelQn00yvE4H46H');                   

        // je crée une session de paiement stripe   
        $checkout_session = Session::create([           
            // je définis mes méthodes de paiement 
            'payment_method_types' => ['card'],
            // je définis le client stripe avec l'email de l'utilisateur connecté pour ne pas avoir à le renseigner à chaque paiement 
            'customer_email' => $this->getUser()->getEmail(),
            // line_items correspond aux produits que je souhaite acheter 
            'line_items' => [[
              $product_for_stripe
            ]],
            // je définis le mode de paiement
            'mode' => 'payment',
            // je définis l'url de redirection en cas de succès avec l'id de la commande pour la récupérer en bdd
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            // 'success_url' => 'http://127.0.0.1:8000/commande/merci/{CHECKOUT_SESSION_ID}',
            // je définis l'url de redirection en cas d'échec
            'cancel_url' => $YOUR_DOMAIN . '/commande/echec/{CHECKOUT_SESSION_ID}',
          ]);  
          
          // j'injecte la session de paiement stripe dans la commande grace à la méthode setStripeSessionId definie dans l'entité Order
          $order->setStripeSessionId($checkout_session->id);       
          // je flush la commande
          $entityManager->flush();
          
          // je retourne la réponse de stripe en json avec le JSONResponse de symfony 
          $response = new JsonResponse(['id' => $checkout_session->id]);
          // je fais un return de la response
          return $response;
        
    }
}



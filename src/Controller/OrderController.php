<?php

namespace App\Controller;


use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OrderController extends AbstractController
{
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart): Response
    {
       // si l'utilisateur n'a pas d'adresse, on le redirige vers la page des adresses
        if(!$this->getUser()->getAddresses()->getValues()){
           return $this->redirectToRoute('app_adress');
         }

          // on crée le formulaire
          $form = $this->createForm(OrderType::class, null, [
            // je passe à la vue l'utilisateur connecté pour pouvoir récupérer ses adresses
            'user'=> $this->getUser(),        
        ]);
      
        return $this->render('order/commande.html.twig', [   
            'form' => $form->createView(),         
            'cart' => $cart->getFull(),
        ]);
    }

    // je crée une route pour le récapitulatif de la commande 
    #[Route('/commande/recapitulatif', name: 'order_recap', methods: ['POST'])]
    public function add(Cart $cart, Request $request, EntityManagerInterface $entityManager): Response
    {
            // on crée le formulaire
            $form = $this->createForm(OrderType::class, null, [
                // je passe à la vue l'utilisateur connecté pour pouvoir récupérer ses adresses
                'user'=> $this->getUser(),        
            ]);            
            // je gère la soumission du formulaire
            $form->handleRequest($request);
            // si le formulaire est soumis et valide je récupère les données
            if($form->isSubmitted() && $form->isValid()){
                // je récupère la date de la commande
                $date = new \DateTime();
                // je récupère les données du formulaire correspondant au transporteur choisi 
                $carriers = $form->get('carriers')->getData();
                // je récupère les données du formulaire correspondant à l'adresse de livraison  
                $delivery = $form->get('addresses')->getData(); 
                // je récupère le nom et le prénom de l'utilisateur connecté      
                $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
                // je récupère le numéro de téléphone de l'utilisateur connecté
                $delivery_content .= '<br/>'.$delivery->getPhone();

                // si la compagnie n'est pas vide, je l'affiche
                if($delivery->getCompany()){
                    // je récupère la compagnie de l'utilisateur connecté 
                    $delivery_content .= '<br/>'.$delivery->getCompany();
                }
                // je récupère l'adresse de livraison
                $delivery_content .= '<br/>'.$delivery->getAdress();
                // si le code postale
                $delivery_content .= '<br/>'.$delivery->getPostal().' '.$delivery->getCity();
                // je récupère le pays de livraison
                $delivery_content .= '<br/>'.$delivery->getCountry();
              

                // enregistrement de ma commande en bdd 
                $order = new Order();
                // je récupère l'utilisateur connecté
                $order->setUser($this->getUser());
                // je récupère la date de la commande
                $order->setCreatedAt($date);
                // je récupère le nom du transporteur
                $order->setCarrierName($carriers->getName());
                // je récupère le prix du transporteur
                $order->setCarrierPrice($carriers->getPrice());
                // je récupère le contenu de l'adresse de livraison de la variable $delivery_content
                $order->setDelivery($delivery_content);
                // je récupère le statut de la commande
                $order->setIsPaid(0);
                
                    // je fais persister mes données en bdd
                    $entityManager->persist($order);

                
                // je récupère le contenu du panier 
                foreach($cart->getFull() as $product){
                
                    $orderDetails = new OrderDetails();
                    // je récupère les données de ma commande
                    $orderDetails->setMyOrder($order);
                    // je récupère le nom du produit
                    $orderDetails->setProduct($product['product']->getName());
                    // je récupère la quantité du produit
                    $orderDetails->setQuantity($product['quantity']);
                    // je récupère le prix du produit
                    $orderDetails->setPrice($product['product']->getPrice());
                    // je récupère le total du produit (prix * quantité)
                    $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                    // je fais persister mes détails de commande en bdd 
                   $entityManager->persist($orderDetails);                   
                 
                   
                }
            
                    // j'envoie en bdd mes données
                     //$entityManager->flush();

                    // // integration de stripe clé api
                    // Stripe::setApikey('sk_test_51Mk7OXGR96Yhx7AaULpIVLff0IN5jxP3f3BinbfA8n1bIx4uKfMlBCyhxJaetnTNDFM4KNhMq392WshpjKmhelQn00yvE4H46H');
                   

                    // // je crée une session de paiement stripe   
                    // $checkout_session = Session::create([                     
                       
                    //     // je définis mes méthodes de paiement 
                    //     'payment_method_types' => ['card'],
                    //     // line_items correspond aux produits que je souhaite acheter 
                    //     'line_items' => [[
                    //       $product_for_stripe
                    //     ]],
                    //     // // je définis le mode de paiement
                    //     'mode' => 'payment',
                    //     // je définis l'url de redirection en cas de succès
                    //     'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                    //     // 'success_url' => 'http://127.0.0.1:8000/commande/merci/{CHECKOUT_SESSION_ID}',
                    //     // je définis l'url de redirection en cas d'échec
                    //     'cancel_url' => $YOUR_DOMAIN . '/commande/echec/{CHECKOUT_SESSION_ID}',
                    //   ]);                    
                     
             
                    return $this->render('order/recapitulatif.html.twig', [
                        // je passe à la vue le panier
                        'cart' => $cart->getFull(),
                        // je passe à la vue les transporteurs
                        'carrier' => $carriers,
                        // je passe à la vue le contenu de l'adresse de livraison 
                        'delivery' => $delivery_content                        
                    ]);
            } 
            // si le formulaire n'est pas soumis ou n'est pas valide, je redirige vers la page de commande
            return $this->redirectToRoute('cart');
    }

}

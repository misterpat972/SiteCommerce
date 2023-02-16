<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{   
    #[Route('/mon-panier', name: 'cart')]
    public function index(Request $request, ProductRepository $productRepository ): Response
    {
        $cart = [];  
        // récupération du panier
        foreach ($request->getSession()->get('cart') as $id => $quantity) {
            $cart[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        // affichage du panier


        return $this->render('cart/panier.html.twig',[
            'cart' => $cart
        ]);
    }
    // ajout d'un produit dans le panier 
    #[Route('/mon-panier/add/{id}', name: 'add_cart')]
        public function addCart(Cart $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }
    // suppression d'un produit dans le panier
    #[Route('/mon-panier/remove', name: 'remove_cart')]
        public function removeCart(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }
}

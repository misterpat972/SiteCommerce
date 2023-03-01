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
    public function index(Cart $cart): Response
    {
        // affichage du panier
        return $this->render('cart/panier.html.twig',[
            'cart' => $cart->getFull()
        ]);
    }
    // ajout du nombre du produit dans le panier 
    #[Route('/mon-panier/add/{id}', name: 'add_cart')]
        public function addCart(Cart $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }
    // soustraction du nombre du produit dans le panier
    #[Route('/mon-panier/sub/{id}', name: 'sub_product')]
        public function sub(Cart $cart, $id): Response
    {
        $cart->sub($id);
        return $this->redirectToRoute('cart');
    }
    // suppression d'un produit dans le panier
    #[Route('/mon-panier/remove/{id}', name: 'remove_product')]
        public function removeProduct(Cart $cart, $id): Response
    {
        $cart->removeProduct($id);
        return $this->redirectToRoute('cart');
    }
    // suppression Total du panier
    #[Route('/mon-panier/remove', name: 'remove_cart')]
        public function removeCart(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }

}

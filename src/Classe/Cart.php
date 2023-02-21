<?php

namespace App\Classe;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class Cart 
{
   private $requestStack;
   private $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }
    // voir la totalité du panier 
    public function getFull()
    {   // function get() récupère le panier dans la session
        $cart = $this->get();
        $fullCart = [];
        foreach($cart as $id => $quantity){
            $product_objet = $this->productRepository->find($id);
            // si le produit n'existe pas on le supprime du panier
            if(!$product_objet){
                $this->removeProduct($id);
                continue;
            }
            // si le produit existe on l'ajoute au panier
            $fullCart[] = [
                'product' => $product_objet,
                'quantity' => $quantity
            ];
        }
        return $fullCart;
    }
    // ajout d'un produit dans le panier
    public function add($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
       return $session->set('cart', $cart);

    }
    // soustraction d'un produit dans le panier
    public function sub($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if($cart[$id] > 1) {
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        
       return $session->set('cart', $cart);

    }
    // récupération du panier
    public function get()
    {
        $session = $this->requestStack->getSession();
        return $session->get('cart');
    }
    // suppression d'un produit dans le panier
    public function removeProduct($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if(!empty($cart[$id])){
            unset($cart[$id]);
        }
      return  $session->set('cart', $cart);
    }
    // suppression du panier
    public function remove()
    {
        $session = $this->requestStack->getSession();
        return $session->remove('cart');
    }
}
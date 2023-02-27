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
        // récupération du panier dans la session
        $cart = $session->get('cart', []);
        // si le produit existe déjà dans le panier on ajoute 1
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            // si le produit n'existe pas dans le panier on l'ajoute
            $cart[$id] = 1;
        }
       // on enregistre le panier dans la session 
       return $session->set('cart', $cart);

    }
    // soustraction d'un produit dans le panier
    public function sub($id)
    {
        $session = $this->requestStack->getSession();
        // récupération du panier dans la session
        $cart = $session->get('cart', []);
        // si le produit est supérieur à 1 on soustrait 1
        if($cart[$id] > 1) {
            $cart[$id]--;
        }else{
            // si le produit est égal à 1 on le supprime du panier
            unset($cart[$id]);
        }
       // on enregistre le panier dans la session 
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
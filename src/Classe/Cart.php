<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;



class Cart 
{
   private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
        $session->set('cart', $cart);

    }
    // récupération du panier
    public function get()
    {
        $session = $this->requestStack->getSession();
        return $session->get('cart');
    }
    // suppression du panier
    public function remove()
    {
        $session = $this->requestStack->getSession();
        return $session->remove('cart');
    }
}
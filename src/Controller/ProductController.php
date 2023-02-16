<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/nos-produits', name: 'products')]
    public function index(ProductRepository $productRepository): Response
    {   
        $products = $productRepository->findAll();
        return $this->render('product/products.html.twig', [
            'products' => $products,            
        ]);
    }

    #[Route('/produit/{id}', name: 'product')]
    public function product(ProductRepository $productRepository, $id): Response
    {   // si l'id n'existe pas, je redirige vers la page des produits
        if(!$id){
            return $this->redirectToRoute('products');
        }
        // je récupère le produit correspondant à l'id
        $product = $productRepository->find($id);
        return $this->render('product/product.html.twig', [
            "product" => $product,                 
        ]);
    }
}

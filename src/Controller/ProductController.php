<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    #[Route('/nos-produits', name: 'products')]
    public function index(ProductRepository $productRepository, Request $request): Response
    {   
        // je crée un objet de la classe Search
        $Search = new Search();   
         // je crée un formulaire de recherche qui sera utilisé dans le header du site pour rechercher des produits par nom ou par catégorie
        $form = $this->createForm(SearchType::class, $Search);
        // je récupère les données du formulaire
        $form->handleRequest($request);        
        // si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            // je récupère les données du formulaire
            $data = $form->getData();
            // je récupère les produits correspondant à la recherche
            $products = $productRepository->findWithSearch($data);                              
         }else{
            // je récupère tous les produits
            $products = $productRepository->findAll();
        }
        // $products = $productRepository->findAll();
        return $this->render('product/products.html.twig', [
            // je passe à la vue les produits
            'products' => $products,    
            // je passe à la vue le formulaire de recherche
            'form' => $form->createView(),        
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
        // je récupère les produits qui ont la propriété isBest à 1 (true) pour les afficher dans la vue du produit en cours
        $products = $productRepository->findByIsBest(1);
        return $this->render('product/product.html.twig', [
            "product" => $product,
            "products" => $products                 
        ]);
    }
}

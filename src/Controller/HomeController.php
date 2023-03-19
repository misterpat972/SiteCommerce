<?php

namespace App\Controller;


use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {           
        
        $products = $entityManagerInterface->getRepository(Product::class)->findByIsBest(1);
        $headers = $entityManagerInterface->getRepository(Header::class)->findAll();
        return $this->render('home/accueil.html.twig', [
            'products' => $products,
            'headers' => $headers
        ]);
    }
}

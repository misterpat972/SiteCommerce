<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {    
        $email = new Mail();    
        $email->send('misterpat972@gmail.com', 'pato', 'ceci est un essai', 'un, deux trois quatre'); // je passe a la fonction send() les paramètres de la fonction __construct()

        return $this->render('home/accueil.html.twig');
    }
}

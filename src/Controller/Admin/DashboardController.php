<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
       
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //  return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('La Boutique Française');          ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // crud pour les utilisateurs dans le menu de l'admin
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-user', User::class);
        // crud pour les commandes dans le menu de l'admin
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);
        // crud pour les catégories dans le menu de l'admin
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);
        // crud pour les produits dans le menu de l'admin
        yield MenuItem::linkToCrud('Produits', 'fa fa-eye', Product::class);
        // crud pour les transporteurs dans le menu de l'admin
        yield MenuItem::linkToCrud('Transporteurs', 'fa fa-truck', Carrier::class);
      
    }
}

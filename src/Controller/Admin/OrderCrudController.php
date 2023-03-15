<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use PhpParser\Node\Expr\AssignOp\Mod;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    // ici on va modifier les actions de la page index avec la méthode configureActions() de EasyAdmin
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail');
    }
    
    // ici on va fenir configurer l'odre de tri des commandes
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC']);
    }


    // ici on va modifier les champs de la page index avec la méthode configureFields() de EasyAdmin
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAT', 'Passée le'),
            TextField::new('user.getFullName', 'Utilisateur'),        
            // NumberField::new('total')->setNumDecimals(2), 
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('total', 'Total Produit')->setCurrency('EUR'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payée ?'),   
            // ici on viens récupérer le détail de la commande (hideOnIndex() permet de ne pas afficher le détail de la commande sur la page index)
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex(),                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
        ];
    }
    
}

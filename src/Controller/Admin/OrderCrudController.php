<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use PhpParser\Node\Expr\AssignOp\Mod;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

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
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAT', 'Passée le'),
            TextField::new('user.getFullName', 'Utilisateur'),        
            NumberField::new('total')->setNumDecimals(2), 
            BooleanField::new('isPaid', 'Payée ?'),                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        ];
    }
    
}

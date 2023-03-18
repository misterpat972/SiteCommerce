<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use PhpParser\Node\Expr\AssignOp\Mod;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    // ici on va modifier les actions de la page index avec la méthode configureActions() de EasyAdmin
    public function configureActions(Actions $actions): Actions
    {       // ici on va créer une nouvelle action pour modifier l'état de la commande en "En préparation"
            $updatePreparation = Action::new('updatePreparation', 'En préparation', 'fas fa-box-open')
            // ici on va créer une nouvelle route pour modifier l'état de la commande en "En préparation"
            ->linkToCrudAction('updatePreparation');
            // ici on va créer une nouvelle action pour modifier l'état de la commande en "En livraison"
            $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')
            // ici on va créer une nouvelle route pour modifier l'état de la commande en "En livraison"
            ->linkToCrudAction('updateDelivery');

        
            return $actions
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery)
            ->add('index', 'detail');
    }

    // ici on va créer une nouvelle méthode pour modifier l'état de la commande en "En préparation"
    public function updatePreparation(AdminContext $context, EntityManagerInterface $entityManagerInterface, AdminUrlGenerator $adminUrlGenerator)
    {
        
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $entityManagerInterface->flush();
         // on crée un message flash pour confirmer la modification de l'état de la commande
        $this->addFlash('success', "La commande n°{$order->getReference()} est en préparation");
        // on redirige vers la page index des commandes grace à AdminUrlGenerator qui permet de générer une url pour une action de EasyAdmin
        $url = $adminUrlGenerator        
        ->setController(OrderCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();

          // on envoie un email à l'utilisateur pour lui confirmer que sa commande est en préparation
          $email = new Mail();
          $content = "Bonjour ".$order->getUser()->getFirstName()."<br/>Votre commande n°".$order->getReference()." est en cours de préparation";
          $email->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'Votre commande sur la boutique La Boutique Française est en cours de préparation', $content);
                   


        // on redirige vers la page index des commandes grace à AdminUrlGenerator qui permet de générer une url pour une action de EasyAdmin "redirect"
        return $this->redirect($url);
       
    }

    // ici on va créer une nouvelle méthode pour modifier l'état de la commande en "En livraison"
       // ici on va créer une nouvelle méthode pour modifier l'état de la commande en "En préparation"
       public function updateDelivery(AdminContext $context, EntityManagerInterface $entityManagerInterface, AdminUrlGenerator $adminUrlGenerator)
       {
           
           $order = $context->getEntity()->getInstance();
           $order->setState(4);
           $entityManagerInterface->flush();
            // on crée un message flash pour confirmer la modification de l'état de la commande
           $this->addFlash('success', "La commande n°{$order->getReference()} est en cours de livraison");
           // on redirige vers la page index des commandes grace à AdminUrlGenerator qui permet de générer une url pour une action de EasyAdmin
           $url = $adminUrlGenerator        
           ->setController(OrderCrudController::class)
           ->setAction(Action::INDEX)
           ->generateUrl();
           // on redirige vers la page index des commandes grace à AdminUrlGenerator qui permet de générer une url pour une action de EasyAdmin "redirect"
           return $this->redirect($url);
          
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
            // ici on va créer un champ virtuel pour afficher l'adresse de livraison de la commande et onlyOnDetail() pour l'afficher uniquement sur la page detail
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),       
            // NumberField::new('total')->setNumDecimals(2), 
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('total', 'Total Produit')->setCurrency('EUR'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            // BooleanField::new('isPaid', 'Payée ?'), 
            // avec ChoiceField on va pouvoir afficher les états de la commande sous forme de liste déroulante 
            ChoiceField::new('state', 'Etat')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Préparée' => 3,
                'En cours de livraison' => 4,
                'Livré' => 5,
            ]),
            // ici on viens récupérer le détail de la commande (hideOnIndex() permet de ne pas afficher le détail de la commande sur la page index)
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex(),                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
        ];
    }
    
}

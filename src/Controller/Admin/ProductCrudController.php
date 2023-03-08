<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        // return [
        //     IdField::new('id'),
        //     TextField::new('title'),
        //     TextEditorField::new('description'),
        // ];

        // je retourne un tableau de champs de formulaire pour la création et l'édition des produits 
        return[
            TextField::new('name'),
            // je crée un nouveau champ de type SlugField qui va permettre de générer automatiquement le slug à partir du nom du produit
            SlugField::new('slug')->setTargetFieldName('name'),
            // je crée un nouveau champ de type ImageField qui est un champ personnalisé de EasyAdmin qui permet de gérer les images en upload
            ImageField::new('illustration')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads/')
            // ici je crée un nouveau nom de fichier aléatoire pour l'image uploadée 
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
            // je crée un nouveau champ de type TextField qui est un champ personnalisé de EasyAdmin
            TextField::new('subtitle'),
            // je crée un nouveau champ de type TextareaField qui est un champ personnalisé de EasyAdmin
            TextareaField::new('description'),
            // je crée un nouveau champ de type MoneyField qui est un champ personnalisé de EasyAdmin
            // MoneyField::new('price')->setCurrency('EUR'),
            NumberField::new('price')->setNumDecimals(2),
            // je crée un nouveau champ de type AssociationField qui est un champ personnalisé de EasyAdmin qui permet de lier un produit à une catégorie
            // AssociationField::new('category')
            AssociationField::new('category'),
        ];

    }
    
}

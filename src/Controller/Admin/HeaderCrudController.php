<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
           
            TextField::new('title', 'Titre du header'),
            // texteareafield est un champ personnalisé de easyadmin que l'on veut utiliser pour le contenu du header
            TextareaField::new('content', 'Contenu du header'),
            TextField::new('btnTitle', 'Texte du bouton'),
            TextField::new('btnUrl', 'Url du bouton'),
            // je crée un nouveau champ de type ImageField qui est un champ personnalisé de EasyAdmin qui permet de gérer les images en upload
            ImageField::new('illustration')->setBasePath('uploads/')->setUploadDir('public/uploads/')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
        ];
    }
    
}

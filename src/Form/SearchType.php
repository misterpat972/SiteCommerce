<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SearchType extends AbstractType {
    
    // on crée un formulaire de recherche qui sera utilisé dans le header du site pour rechercher des produits par nom ou par catégorie avec la fonction BuildForm
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre recherche ...',
                    'class' => 'form-control-sm'
                ]
            ])
            // champ de type EntityType qui permet de créer un champ de formulaire qui permet de sélectionner une ou plusieurs entités
            ->add('categories', EntityType::class, [
                'label' => false,
                // ici on indique que le champ n'est pas obligatoire
                'required' => false,
                // ici on indique la classe qui représente les catégories
                'class' => Category::class,
                // ici on indique le champ peux être multiple
                'multiple' => true,
                // ici on indique que le champ est étendu
                'expanded' => true
                
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ]);
       
    }
    
    
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // ici on indique la classe qui représente le formulaire
            'data_class' => Search::class,
            // ici in indique la méthode d'envoi du formulaire GET pour récupérer les données dans l'URL 
            'method' => 'GET',
            // ici on indique qu'on ne veut pas de protection CSRF
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        // on indique ici que le formulaire n'a pas de préfixe de bloc sinon on aura un bloc search_search dans l'URL
        return '';
    }
   
}
<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   // je récupère l'utilisateur connecté
        $user = $options['user'];
        
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choisissez une adresse',
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                // choix à false pour ne pas avoir de checkbox
                'multiple' => false,
                // expanded à true pour avoir un select en radio
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // je passe à la vue un tableau vide pour ne pas avoir d'erreur
           'user' => array(),
           
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   // je récupère l'utilisateur connecté
        $user = $options['user'];
        
        $builder
            ->add('addresses', EntityType::class, [
                'label' => false,
                'required' => true,
                // je passe à la vue l'entité Address pour pouvoir récupérer les adresses de l'utilisateur connecté
                'class' => Address::class,
                // je passe à la vue les adresses de l'utilisateur connecté
                'choices' => $user->getAddresses(),
                // choix à false pour ne pas avoir de checkbox
                'multiple' => false,
                // expanded à true pour avoir un select en radio
                'expanded' => true,
            ])

            ->add('carriers', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                // je passe à la vue l'entité Address pour pouvoir récupérer les adresses de l'utilisateur connecté
                'class' => Carrier::class,
                
                // choix à false pour ne pas avoir de checkbox
                'multiple' => false,
                // expanded à true pour avoir un select en radio
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-block btn-success'
                ]
            ])
        ;
    }
    // je passe à la vue l'entité Order pour pouvoir récupérer les adresses de l'utilisateur connecté
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // je passe à la vue un tableau vide pour ne pas avoir d'erreur
           'user' => array(),
           
        ]);
    }
}

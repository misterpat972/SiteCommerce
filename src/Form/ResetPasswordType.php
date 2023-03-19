<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('new_password', RepeatedType::class, [
            // on ajoute un label au champ
            'label' => 'Mot de passe',                
            'type' => PasswordType::class,
            // si les mots de passe ne sont pas identiques, on affiche un message d'erreur
            'invalid_message' => 'Les mots de passe doivent être identiques',
            // requiert que les deux champs soient remplis
            'required' => true,
            // on ajoute un champ avec le label "mot de passe"
            'first_options' => ['label' => 'Mot de passe', 'attr' => ['placeholder' => 'Votre mot de passe']],
            // on ajoute un second champ pour la confirmation avec le label "confirmation du mot de passe"
            'second_options' => ['label' => 'Confirmation du mot de passe', 'attr' => ['placeholder' => 'Confirmez votre mot de passe']],
        ])       
        // on ajoute un bouton de type submit
        ->add('submit', SubmitType::class, [
            'label' => 'Metre à jour mon mot de passe',
            'attr' => [
                'class' => 'btn btn-block btn-info'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

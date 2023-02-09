<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                // on ajoute un label au champ
                'label' => 'Prénom',
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                // on ajoute un label au champ
                'label' => 'Nom',
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                // on ajoute un label au champ
                'label' => 'Email',
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])           
            ->add('password', PasswordType::class, [
                // on ajoute un label au champ
                'label' => 'Mot de passe',
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Votre mot de passe'
                ]
            ])
            ->add('passwordConfirm', PasswordType::class, [
                // on ajoute un label au champ
                'label' => 'Confirmation du mot de passe',
                 // on ne veut pas que le champ soit lié à une propriété de l'entité User
                'mapped' => false,
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe'
                ]
            ])
            // on ajoute un bouton de type submit
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

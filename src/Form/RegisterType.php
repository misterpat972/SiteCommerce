<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                // on ajoute un label au champ
                'label' => 'Prénom',
                // on ajoute une contrainte de validation sur le champ firstname
                'constraints' => new Length(['min' => 4, 'max' => 20, 
                'minMessage' => 'Votre prénom doit comporter au moins 4 caractères',
                'maxMessage' => 'Votre prénom ne peut pas comporter plus de 20 caractères']),
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                // on ajoute un label au champ
                'label' => 'Nom',
                // on ajoute une contrainte de validation sur le champ firstname
                'constraints' => new Length(['min' => 4, 'max' => 20, 
                'minMessage' => 'Votre nom doit comporter au moins 4 caractères',
                'maxMessage' => 'Votre nom ne peut pas comporter plus de 20 caractères']),
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                // on ajoute un label au champ
                'label' => 'Email',
                // on ajoute une contrainte de validation sur le champ firstname
                'constraints' => new Length(['min' => 4, 'max' => 50, 
                'minMessage' => 'Votre mail doit comporter au moins 4 caractères',
                'maxMessage' => 'Votre mail ne peut pas comporter plus de 20 caractères']),
                // on ajoute un attribut placeholder au champ
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])
            ->add('password', RepeatedType::class, [
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
            // :TODO: uncomment this block to add password fields           
            // ->add('password', PasswordType::class, [
            //     // on ajoute un label au champ
            //     'label' => 'Mot de passe',
            //     // on ajoute un attribut placeholder au champ
            //     'attr' => [
            //         'placeholder' => 'Votre mot de passe'
            //     ]
            // ])
            // ->add('passwordConfirm', PasswordType::class, [
            //     // on ajoute un label au champ
            //     'label' => 'Confirmation du mot de passe',
            //      // on ne veut pas que le champ soit lié à une propriété de l'entité User
            //     'mapped' => false,
            //     // on ajoute un attribut placeholder au champ
            //     'attr' => [
            //         'placeholder' => 'Confirmez votre mot de passe'
            //     ]
            // ])
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

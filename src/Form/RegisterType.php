<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Adresse email"]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'maxlength' => 20,
                        'placeholder' => 'Mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'maxlength' => 20,
                        'placeholder' => 'Confirmation du mot de passe'
                    ]
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Prénom"]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Nom"]
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'male',
                    'Femme' => 'female'
                ],
                'label' => 'Sexe'
            ])
            ->add('birthdate', DateType::class, array(
                    'widget' => 'single_text',
                    'label' => 'Date de Naissance',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('submit', SubmitType::class, array(
                'label' => "S'inscrire",
                'attr' => array(
                    'class' => 'button button-rounded btn-block nott ls0 m-0',
                    'style' => "width: 100%;"
                )
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

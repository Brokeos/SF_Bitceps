<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastPassword', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'maxlength' => 20,
                    'placeholder' => 'Ancien mot de passe'
                ],
                'constraints' => [
                    new SecurityAssert\UserPassword([
                        'message' => 'Le mot de passe est incorrect',
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'maxlength' => 20,
                        'placeholder' => 'Nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'maxlength' => 20,
                        'placeholder' => 'Confirmation du nouveau mot de passe'
                    ]
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        'max' => 32,
                        'minMessage' => "Le mot de passe doit contenir au minimum 6 caractères !",
                        'maxMessage' => "Le mot de passe doit contenir au maximum 32 caractères !"
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, array(
                'label' => "Modifier",
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
            // Configure your form options here
        ]);
    }
}

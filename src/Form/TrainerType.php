<?php

namespace App\Form;

use App\Entity\Trainer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Nom"]
            ])
            ->add('category', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Nom"]
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label'=> 'Image',
                'multiple' => false
            ])
            ->add('color', ColorType::class, [
                'label' => 'Couleur'
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
            'data_class' => Trainer::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Lesson;
use App\Entity\Trainer;
use App\Helpers\Dates;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Nom"]
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Description"]
            ])
            ->add('day', ChoiceType::class, [
                'choices' => array_combine(array_map('ucfirst', Dates::$days), Dates::$days),
                'label' => 'Jour'
            ])
            ->add('trainer', EntityType::class, [
                'class' => Trainer::class,
                'choice_label' => 'name',
                'label' => 'Entraineur'
            ])
            ->add('hourStart', TimeType::class, [
                'label' => 'Heure de début',
                'input' => 'string',
                'input_format' => 'H:i',
                'widget' => 'single_text',
            ])
            ->add('hourEnd', TimeType::class, [
                'label' => 'Heure de fin',
                'input' => 'string',
                'input_format' => 'H:i',
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }
}

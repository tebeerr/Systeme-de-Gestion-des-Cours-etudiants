<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Examen;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Libelle', null, [
                'label' => 'Exam Title',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter exam title']
            ])
            ->add('DateExamen', null, [
                'label' => 'Exam Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'Libelle',
                'label' => 'Course',
                'placeholder' => 'Select a course',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Examen::class,
        ]);
    }
}

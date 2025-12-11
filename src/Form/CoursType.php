<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Cours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Libelle', null, [
                'label' => 'Course Title',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter course title']
            ])
            ->add('Description', null, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter course description', 'rows' => 4]
            ])
            ->add('DateDebut', null, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('DateFin', null, [
                'label' => 'End Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('classe', EntityType::class, [
                'class' => Classe::class,
                'choice_label' => 'Libelle',
                'label' => 'Class',
                'placeholder' => 'Select a class',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}

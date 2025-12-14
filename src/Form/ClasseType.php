<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Libelle', null, [
                'label' => 'Class Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter class name']
            ])
            ->add('students', EntityType::class, [
                'class' => User::class,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_STUDENT%')
                        ->orderBy('u.email', 'ASC');
                },
                'choice_label' => 'email',
                'label' => 'Select Students',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'size' => 10
                ],
                'help' => 'Hold Ctrl (or Cmd on Mac) to select multiple students'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}

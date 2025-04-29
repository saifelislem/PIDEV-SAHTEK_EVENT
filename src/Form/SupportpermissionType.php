<?php

namespace App\Form;

use App\Entity\Support;
use App\Entity\Supportpermission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SupportpermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('permission_type', ChoiceType::class, [
            'label' => 'Type de permission',
            'choices' => [
                'Lecture' => Supportpermission::PERMISSION_READ,
                'Téléchargement' => Supportpermission::PERMISSION_DOWNLOAD,
            ],
            'attr' => ['class' => 'form-control'],
        ])

            ->add('startDate', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
               
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
               
            ])
            ->add('support', EntityType::class, [
                'class' => Support::class,
                'choice_label' => 'titre',
                'label' => 'Support associé',
                'placeholder' => 'Choisir un support',
                'attr' => ['class' => 'form-control'],
              
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supportpermission::class,
        ]);
    }
}
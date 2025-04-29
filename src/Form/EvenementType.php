<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextareaType::class)
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control'],
                'input' => 'datetime_immutable',
            ])
            ->add('lieu', TextType::class)
            ->add('capacite_max', IntegerType::class)
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Conférences' => 'Conferences',
                    'Séminaires' => 'Seminaires',
                    'Ateliers Pratiques' => 'Ateliers Pratiques',
                    'Compétitions' => 'Competitions',
                ],
                'attr' => ['class' => 'form-control shadow-sm selectpicker'],
                'required' => true,
                'choice_attr' => function ($choice, $key, $value) {
                    return ['data-content' => $key];
                },
            ])
        ;

        if ($options['is_edit']) {
            $builder->add('statut', ChoiceType::class, [
                'choices' => [
                    'Actif' => 'Actif',
                    'Annulé' => 'Annulé',
                    'Terminé' => 'Terminé',
                ],
                'attr' => ['class' => 'form-control shadow-sm selectpicker'],
                'required' => true,
                'choice_attr' => function ($choice, $key, $value) {
                    return ['data-content' => $key];
                },
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
            'is_edit' => false,
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}
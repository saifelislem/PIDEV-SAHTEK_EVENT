<?php

namespace App\Form;

use App\Entity\Support;
use App\Entity\Supportpermission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class SupportpermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('permission_type', TextType::class, [
                'label' => 'Type de permission',
                'attr' => ['placeholder' => 'Entrez le type de permission', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le type de permission est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le type doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le type ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Choice([
                        'choices' => ['lecture', 'écriture', 'gestion'],
                        'message' => 'Le type doit être "lecture", "écriture" ou "gestion".',
                    ]),
                ],
            ])
            ->add('role', TextType::class, [
                'label' => 'Rôle',
                'attr' => ['placeholder' => 'Entrez le rôle', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le rôle est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le rôle doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le rôle ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est obligatoire.']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de début doit être aujourd’hui ou dans le futur.',
                    ]),
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La date de fin est obligatoire.']),
                    new GreaterThanOrEqual([
                        'propertyPath' => 'parent.all[startDate].data',
                        'message' => 'La date de fin doit être postérieure ou égale à la date de début.',
                    ]),
                ],
            ])
            ->add('support', EntityType::class, [
                'class' => Support::class,
                'choice_label' => 'titre',
                'label' => 'Support associé',
                'placeholder' => 'Choisir un support',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le support est obligatoire.']),
                ],
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
<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\SuiviReclamation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;

class SuiviReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', TextType::class, [
                'label' => 'Statut',
                'attr' => ['placeholder' => 'Entrez le statut', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le statut est obligatoire.']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le statut ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Choice([
                        'choices' => ['en attente', 'en cours', 'résolu'],
                        'message' => 'Le statut doit être "en attente", "en cours" ou "résolu".',
                    ]),
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => ['placeholder' => 'Ajoutez un commentaire', 'class' => 'form-control', 'rows' => 4],
                'constraints' => [
                    new NotBlank(['message' => 'Le commentaire est obligatoire.']),
                    new Length([
                        'min' => 5,
                        'max' => 1000,
                        'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le commentaire ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('reclamation', EntityType::class, [
                'class' => Reclamation::class,
                'choice_label' => function (Reclamation $reclamation) {
                    return sprintf('Réclamation #%d - %s', $reclamation->getId(), $reclamation->getMessage());
                },
                'label' => 'Réclamation associée',
                'placeholder' => 'Choisir une réclamation',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La réclamation est obligatoire.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviReclamation::class,
        ]);
    }
}
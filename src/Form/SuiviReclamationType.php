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

class SuiviReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', TextType::class, [
                'label' => 'Statut',
                'attr' => [
                    'placeholder' => 'Entrez le statut',
                    'class' => 'form-control',
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Ajoutez un commentaire',
                    'class' => 'form-control',
                    'rows' => 4,
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

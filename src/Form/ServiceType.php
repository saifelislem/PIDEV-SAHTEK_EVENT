<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type de service',
                'choices' => [
                    'Transport' => 'Transport',
                    'Hébergement' => 'Hébergement',
                    'Restauration' => 'Restauration',
                ],
                'attr' => ['class' => 'form-control shadow-sm'],
                'label_attr' => ['class' => 'font-weight-bold text-primary'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control shadow-sm', 'rows' => 4, 'placeholder' => 'Décrivez le service'],
                'label_attr' => ['class' => 'font-weight-bold text-primary'],
            ])
            ->add('cout', NumberType::class, [
                'label' => 'Coût',
                'attr' => ['class' => 'form-control shadow-sm', 'placeholder' => 'Entrez le coût en TND'],
                'label_attr' => ['class' => 'font-weight-bold text-primary'],
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement associé',
                'placeholder' => 'Choisir un événement',
                'attr' => ['class' => 'form-control shadow-sm'],
                'label_attr' => ['class' => 'font-weight-bold text-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
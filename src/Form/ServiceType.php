<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type de service',
                'attr' => ['placeholder' => 'Entrez le type de service', 'class' => 'form-control'],
                
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Décrivez le service', 'class' => 'form-control', 'rows' => 4],
                
            ])
            ->add('cout', NumberType::class, [
                'label' => 'Coût',
                'attr' => ['placeholder' => 'Entrez le coût en TND', 'class' => 'form-control'],
               
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement associé',
                'placeholder' => 'Choisir un événement',
                'attr' => ['class' => 'form-control'],
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
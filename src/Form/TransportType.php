<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Service;
use App\Entity\Transport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('heureDepart', TextType::class, [
                'label' => 'Heure de départ',
                'attr' => [
                    'placeholder' => 'HH:MM',
                    'pattern' => '\d{2}:\d{2}',
                    'class' => 'form-control',
                ],
            ])
            ->add('pointDepart', TextType::class, [
                'label' => 'Point de départ',
                'attr' => ['placeholder' => 'Entrez le point de départ', 'class' => 'form-control'],
            ])
            ->add('destination', TextType::class, [
                'label' => 'Destination',
                'attr' => ['placeholder' => 'Entrez la destination', 'class' => 'form-control'],
            ])
            ->add('vehicule', ChoiceType::class, [
                'label' => 'Véhicule',
                'choices' => [
                    'Voiture' => 'voiture',
                    'Bus' => 'bus',
                    'Avion' => 'avion',
                    'Train' => 'train',
                    'Bateau' => 'bateau',
                ],
                'placeholder' => 'Choisir un véhicule',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement associé',
                'placeholder' => 'Choisir un événement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => function (Service $service) {
                    return sprintf( $service->getType());
                },
                'label' => 'Service associé',
                'placeholder' => 'Choisir un service',
                'attr' => ['class' => 'form-control'],
            ])
        ;

        // Transformer la chaîne "HH:MM" en DateTime
        $builder->get('heureDepart')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateFromEntity) {
                    return $dateFromEntity ? $dateFromEntity->format('H:i') : null;
                },
                function ($dateFromForm) {
                    if (!$dateFromForm) {
                        return null;
                    }
                    $dateTime = new \DateTime();
                    $dateTime->setTime(substr($dateFromForm, 0, 2), substr($dateFromForm, 3, 2));
                    return $dateTime;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transport::class,
        ]);
    }
}
<?php
namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Service;
use App\Entity\Transport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;

class TransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La date est obligatoire.']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être aujourd’hui ou dans le futur.',
                    ]),
                ],
            ])
            ->add('heureDepart', TextType::class, [
                'label' => 'Heure de départ',
                'attr' => [
                    'placeholder' => 'HH:MM',
                    'pattern' => '\d{2}:\d{2}',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L’heure de départ est obligatoire.']),
                    new Regex([
                        'pattern' => '/^([01]\d|2[0-3]):([0-5]\d)$/',
                        'message' => 'L’heure de départ doit être au format HH:MM (ex. 14:30).',
                    ]),
                ],
            ])
            ->add('pointDepart', TextType::class, [
                'label' => 'Point de départ',
                'attr' => ['placeholder' => 'Entrez le point de départ', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le point de départ est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le point de départ doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le point de départ ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('destination', TextType::class, [
                'label' => 'Destination',
                'attr' => ['placeholder' => 'Entrez la destination', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La destination est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'La destination doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La destination ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('vehicule', TextType::class, [
                'label' => 'Véhicule',
                'attr' => ['placeholder' => 'Entrez le type de véhicule', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le véhicule est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le véhicule doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le véhicule ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement associé',
                'placeholder' => 'Choisir un événement',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'L’événement est obligatoire.']),
                ],
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => function (Service $service) {
                    return sprintf('%s (ID: %d)', $service->getType(), $service->getId());
                },
                'label' => 'Service associé',
                'placeholder' => 'Choisir un service',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le service est obligatoire.']),
                ],
            ])
        ;

        // Transform string "HH:MM" to "HH:MM:SS" for Doctrine
        $builder->get('heureDepart')
            ->addModelTransformer(new CallbackTransformer(
                function ($stringFromEntity) {
                    // From entity (string "HH:MM:SS") to form (string "HH:MM")
                    return $stringFromEntity ? substr($stringFromEntity, 0, 5) : null;
                },
                function ($stringFromForm) {
                    // From form (string "HH:MM") to entity (string "HH:MM:SS")
                    if (!$stringFromForm) {
                        return null;
                    }
                    return $stringFromForm . ':00';
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
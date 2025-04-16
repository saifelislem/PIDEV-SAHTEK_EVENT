<?php
namespace App\Form;

use App\Entity\Support;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Choice;

class SupportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => 'URL',
                'attr' => ['placeholder' => 'Entrez l’URL du support', 'class' => 'form-control'],
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'L’URL ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Url(['message' => 'Veuillez entrer une URL valide (ex. https://example.com).']),
                ],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'attr' => ['placeholder' => 'Entrez le type de support', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le type est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le type doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le type ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Choice([
                        'choices' => ['image', 'video', 'document'],
                        'message' => 'Le type doit être "image", "video" ou "document".',
                    ]),
                ],
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement Associé',
                'placeholder' => 'Choisir un événement',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Entrez le titre du support', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Support::class,
        ]);
    }
}
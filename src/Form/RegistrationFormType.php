<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Adresse Email',
                ],
                'label_attr' => ['class' => 'sr-only'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre email.']),
                    new Email(['message' => 'Veuillez entrer une adresse email valide.']),
                    new Length(['max' => 180, 'maxMessage' => 'L’email ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Nom',
                ],
                'label_attr' => ['class' => 'sr-only'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nom.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\'\-]+$/',
                        'message' => 'Le nom ne peut contenir que des lettres, espaces, tirets ou apostrophes.',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Prénom',
                ],
                'label_attr' => ['class' => 'sr-only'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre prénom.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\'\-]+$/',
                        'message' => 'Le prénom ne peut contenir que des lettres, espaces, tirets ou apostrophes.',
                    ]),
                ],
            ])
            ->add('nationalite', TextType::class, [
                'label' => 'Nationalité',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Nationalité',
                ],
                'label_attr' => ['class' => 'sr-only'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nationalité.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'La nationalité doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La nationalité ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\'\-]+$/',
                        'message' => 'La nationalité ne peut contenir que des lettres, espaces, tirets ou apostrophes.',
                    ]),
                ],
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => Utilisateur::GENRE_HOMME,
                    'Femme' => Utilisateur::GENRE_FEMME,
                ],
                'expanded' => false, // Crucial pour avoir une liste déroulante
                'multiple' => false, // Sélection unique
                'placeholder' => 'Sélectionnez un genre', // Option important
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true,
            ])
            ->add('mot_de_passe', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mot de passe',
                ],
                'label_attr' => ['class' => 'sr-only'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe.']),
                    new Length([
                        'min' => 8,
                        'max' => 4096,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]+$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule et un chiffre.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
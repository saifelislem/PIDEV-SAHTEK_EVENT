<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez le nom', 'class' => 'form-control'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez le prénom', 'class' => 'form-control'],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Modérateur' => 'ROLE_MODERATOR',
                ],
                'multiple' => true, // Si les rôles sont un tableau
                'expanded' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('mot_de_passe', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrez le mot de passe', 'class' => 'form-control'],
                'mapped' => false, // Pour ne pas mapper directement, gérer dans le contrôleur
                'required' => false,
            ])
            ->add('nationalite', TextType::class, [
                'label' => 'Nationalité',
                'attr' => ['placeholder' => 'Entrez la nationalité', 'class' => 'form-control'],
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                    'Autre' => 'Autre',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez l’email', 'class' => 'form-control'],
            ])
            ->add('permission', ChoiceType::class, [
                'label' => 'Permission',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Actif' => true,
                    'Inactif' => false,
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('verification_token', TextType::class, [
                'label' => 'Token de vérification',
                'attr' => ['placeholder' => 'Token généré automatiquement', 'class' => 'form-control'],
                'required' => false,
            ])
            ->add('is_verified', ChoiceType::class, [
                'label' => 'Vérifié',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => ['class' => 'form-control'],
            ])
            // Champ photo ajouté
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil (JPG ou PNG)',
                'mapped' => false, // Pas directement lié à l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Format autorisé : JPEG ou PNG',
                    ]),
                ],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
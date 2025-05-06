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
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Entrez l’email', 'class' => 'form-control'],
            ])
            ->add('mot_de_passe', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Entrez le mot de passe', 'class' => 'form-control'],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Modérateur' => 'ROLE_MODERATOR',
                ],
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('permission', ChoiceType::class, [
                'label' => 'Permission',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Actif' => true,
                    'Inactif' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('is_verified', ChoiceType::class, [
                'label' => 'Vérifié',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
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
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('verification_token', TextType::class, [
                'label' => 'Token de vérification',
                'required' => false,
                'attr' => ['placeholder' => 'Token généré automatiquement', 'class' => 'form-control'],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil (JPEG ou PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Formats autorisés : JPEG ou PNG',
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

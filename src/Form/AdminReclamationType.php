<?php
namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AdminReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $utilisateur) {
                    return $utilisateur->getNom() . ' ' . $utilisateur->getPrenom() . ' (' . $utilisateur->getEmail() . ')';
                },
                'label' => 'Utilisateur',
                'placeholder' => 'Choisir un utilisateur',
                'required' => true,
               
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement',
                'placeholder' => 'Choisir un événement',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Décrivez la réclamation',
                    'class' => 'form-control',
                    'rows' => 4,
                ],
               
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (preuve)',
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'form-control-file'],
                
            ])
            ->add('pass', TextType::class, [
                'label' => 'Mot de passe (pour suivi)',
                'attr' => [
                    'placeholder' => 'Entrez un mot de passe pour suivre la réclamation',
                    'class' => 'form-control',
                ],
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
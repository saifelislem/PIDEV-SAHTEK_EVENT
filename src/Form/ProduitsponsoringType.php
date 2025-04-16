<?php

namespace App\Form;

use App\Entity\ContratSponsoring;
use App\Entity\Produitsponsoring;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitsponsoringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez le nom du produit'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Décrivez le produit'],
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['class' => 'form-control', 'min' => 1, 'placeholder' => 'Entrez la quantité'],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'attr' => ['class' => 'form-control', 'step' => '0.01', 'min' => 0, 'placeholder' => 'Entrez le prix'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => true, // Obligatoire comme dans l'entité
                'attr' => ['class' => 'form-control-file'],
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom', // ou un autre champ pertinent
                'label' => 'Utilisateur',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('contratSponsorings', EntityType::class, [
                'class' => ContratSponsoring::class,
                'choice_label' => 'id', // ou un autre champ pertinent
                'multiple' => true,
                'label' => 'Contrats de sponsoring',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produitsponsoring::class,
        ]);
    }
}
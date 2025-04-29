<?php
namespace App\Form;

use App\Entity\SponsorPending;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SponsorRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Utilisateur
            ->add('nom', TextType::class, [
                'label' => 'Nom',
               
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
               
            ])
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de passe',
                
            ])
            ->add('nationalite', TextType::class, [
                'label' => 'Nationalité',
                
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                
            ])
            // Produit
            ->add('produitNom', TextType::class, [
                'label' => 'Nom du produit',
               
            ])
            ->add('produitDescription', TextareaType::class, [
                'label' => 'Description du produit',
                'required' => false,
            ])
            ->add('produitQuantite', IntegerType::class, [
                'label' => 'Quantité',
                
            ])
            ->add('produitPrix', NumberType::class, [
                'label' => 'Prix',
                
            ])
            ->add('produitImage', FileType::class, [
                'label' => 'Image du produit',
                'required' => false,
               
            ])
            // Contrat
            ->add('contratMontant', NumberType::class, [
                'label' => 'Montant du contrat',
               
            ])
            ->add('contratDescription', TextareaType::class, [
                'label' => 'Description du contrat',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SponsorPending::class,
        ]);
    }
}
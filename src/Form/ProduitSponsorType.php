<?php
namespace App\Form;

use App\Entity\SponsorPending;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitSponsorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produitNom', TextType::class, [
                'label' => 'Nom du produit',
                
            ])
            ->add('produitDescription', TextareaType::class, [
                'label' => 'Description du produit',
                
            ])
            ->add('produitQuantite', NumberType::class, [
                'label' => 'Quantité',
               
            ])
            ->add('produitPrix', NumberType::class, [
                'label' => 'Prix (€)',
                'scale' => 2,
               
            ])
            ->add('produitImage', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
            ])
            ->add('contratMontant', NumberType::class, [
                'label' => 'Montant du contrat (€)',
                'scale' => 2,
                'required' => false,
               
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
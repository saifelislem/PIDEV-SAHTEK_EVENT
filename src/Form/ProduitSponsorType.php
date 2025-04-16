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
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ProduitSponsorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produitNom', TextType::class, [
                'label' => 'Nom du produit',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Length(['max' => 255, 'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('produitDescription', TextareaType::class, [
                'label' => 'Description du produit',
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire.']),
                    new Length(['min' => 10, 'minMessage' => 'La description doit contenir au moins {{ limit }} caractères.']),
                ],
            ])
            ->add('produitQuantite', NumberType::class, [
                'label' => 'Quantité',
                'constraints' => [
                    new NotBlank(['message' => 'La quantité est obligatoire.']),
                    new Positive(['message' => 'La quantité doit être positive.']),
                ],
            ])
            ->add('produitPrix', NumberType::class, [
                'label' => 'Prix (€)',
                'scale' => 2,
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est obligatoire.']),
                    new Positive(['message' => 'Le prix doit être positif.']),
                ],
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
                'constraints' => [
                    new Positive(['message' => 'Le montant doit être positif.']),
                ],
            ])
            ->add('contratDescription', TextareaType::class, [
                'label' => 'Description du contrat',
                'required' => false,
                'constraints' => [
                    new Length(['min' => 10, 'minMessage' => 'La description doit contenir au moins {{ limit }} caractères.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SponsorPending::class,
        ]);
    }
}
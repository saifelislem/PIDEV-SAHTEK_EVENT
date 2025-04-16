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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SponsorRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Utilisateur
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire.']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank(['message' => 'L’e-mail est obligatoire.']),
                    new Email(['message' => 'L’e-mail doit être valide.']),
                ],
            ])
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('nationalite', TextType::class, [
                'label' => 'Nationalité',
                'constraints' => [
                    new NotBlank(['message' => 'La nationalité est obligatoire.']),
                ],
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le genre est obligatoire.']),
                ],
            ])
            // Produit
            ->add('produitNom', TextType::class, [
                'label' => 'Nom du produit',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du produit est obligatoire.']),
                ],
            ])
            ->add('produitDescription', TextareaType::class, [
                'label' => 'Description du produit',
                'required' => false,
            ])
            ->add('produitQuantite', IntegerType::class, [
                'label' => 'Quantité',
                'constraints' => [
                    new NotBlank(['message' => 'La quantité est obligatoire.']),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'La quantité doit être supérieure à 0.',
                    ]),
                ],
            ])
            ->add('produitPrix', NumberType::class, [
                'label' => 'Prix',
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est obligatoire.']),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Le prix doit être supérieur à 0.',
                    ]),
                ],
            ])
            ->add('produitImage', FileType::class, [
                'label' => 'Image du produit',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2m',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez uploader une image JPEG ou PNG.',
                    ]),
                ],
            ])
            // Contrat
            ->add('contratMontant', NumberType::class, [
                'label' => 'Montant du contrat',
                'constraints' => [
                    new NotBlank(['message' => 'Le montant est obligatoire.']),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Le montant doit être supérieur à 0.',
                    ]),
                ],
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
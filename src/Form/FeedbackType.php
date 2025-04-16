<?php
namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre commentaire',
                    'class' => 'form-control',
                    'rows' => 4,
                ],
            ])
            ->add('note', IntegerType::class, [
                'label' => 'Note (1-5)',
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'placeholder' => 'Entrez une note de 1 à 5',
                    'class' => 'form-control',
                ],
            ])
            ->add('pass', TextType::class, [
                'label' => 'Mot de passe (pour suivi)',
                'attr' => [
                    'placeholder' => 'Entrez un mot de passe pour suivre votre feedback',
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
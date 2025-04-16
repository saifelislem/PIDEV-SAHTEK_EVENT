<?php
namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextareaType::class)
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text', // Champ texte avec picker
                'html5' => true, // Calendrier natif HTML5
                'attr' => ['class' => 'form-control'], // Style Bootstrap
                'input' => 'datetime_immutable', // Retourne DateTimeImmutable
                // Pas de 'format' ici, HTML5 impose yyyy-MM-ddTHH:mm
            ])
            ->add('lieu', TextType::class)
            ->add('statut', TextType::class)
            ->add('capacite_max', IntegerType::class)
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('type', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
<?php
namespace App\Form;

use App\Entity\Support;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SupportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichier', FileType::class, [
                'label' => false, 
                'mapped' => false,
                'required' => false,  
            ])
           
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Document' => Support::TYPE_DOCUMENT,
                    'PPT' => Support::TYPE_PPT,
                    'Vidéo' => Support::TYPE_VIDEO,
                ],
                'required' => true,  
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement Associé',
                'placeholder' => 'Choisir un événement',
                'attr' => ['class' => 'form-control'],
                'required' => false,  
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Entrez le titre du support', 'class' => 'form-control'],
                'required' => false,  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Support::class,
        ]);
    }
}

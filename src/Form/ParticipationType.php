<?php
namespace App\Form;

use App\Entity\Participation;
use App\Entity\Utilisateur;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParticipationType extends AbstractType
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
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_inscription', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date d\'inscription',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('moyenPaiement', ChoiceType::class, [
                'choices' => [
                    'Carte Bancaire' => 'carte_bancaire',
                    'Espèces' => 'especes',
                    'Virement' => 'virement',
                ],
                'required' => true,
                'label' => 'Moyen de Paiement',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
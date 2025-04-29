<?php
namespace App\Form;

use App\Entity\ContratSponsoring;
use App\Entity\Evenement;
use App\Entity\Produitsponsoring;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratSponsoringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $utilisateur) {
                    return $utilisateur->getNom() . ' ' . $utilisateur->getPrenom() . ' (' . $utilisateur->getEmail() . ')';
                },
                'label' => 'Utilisateur',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nom',
                'label' => 'Événement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('produitsponsorings', EntityType::class, [
                'class' => Produitsponsoring::class,
                'choice_label' => 'nom', // Assurez-vous que Produitsponsoring a un champ 'nom'
                'multiple' => true,
                'expanded' => false, // Liste déroulante multiple (au lieu de checkboxes)
                'label' => 'Produits de sponsoring',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContratSponsoring::class,
        ]);
    }
}
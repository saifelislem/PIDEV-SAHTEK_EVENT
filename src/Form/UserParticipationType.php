<?php
namespace App\Form;

use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
<?php

namespace App\Form;

use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use App\Entity\User; 
use App\Entity\BilanFinancier; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Annuel' => 'annuel',
                    'Mensuel' => 'mensuel',
                ],
            ])
            ->add('prix', null, [
                'constraints' => [
                    new NotBlank(),
                    new Positive([
                      
                    ]),
                    new GreaterThan([
                        'value' => 0,
                       
                    ]),
                ],
            ])
            ->add('dateDebut')
            ->add('dateFin')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Actif' => 'actif',
                    'Non actif' => 'non_actif',
                ],
            ])
            ->add('idAdherent', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id', 
            ])
            ->add('idBilanFinancier', EntityType::class, [
                'class' => BilanFinancier::class,
                'choice_label' => 'id', // Assuming you want to use the ID as the label
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\BilanFinancier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;


class BilanFinancierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateDebut', DateTimeType::class, [
            'widget' => 'single_text', 
            'constraints' => [
                new GreaterThan([
                    'value' => 'today', 
                    'message' => 'La date de début doit être future à aujourd\'hui.',
                ]),
            ],
        ])
        ->add('dateFin', DateTimeType::class, [
            'widget' => 'single_text',
            'constraints' => [
                new GreaterThan([
                    'propertyPath' => 'parent.all[dateDebut].data',
                    'message' => 'La date de fin doit être postérieure à la date de début.'
                ]),
            ],
        ])
            ->add('salairesCoachs')
            ->add('prixLocation', null, [
                'constraints' => [
                    new NotBlank(),
                    new Positive([
                      
                    ]),
                    new GreaterThan([
                        'value' => 0,
                       
                    ]),
                ],
            ])
            ->add('revenusAbonnements')
            ->add('revenusProduits')
            ->add('depenses', null, [
                'constraints' => [
                    new NotBlank(),
                    new Positive([
                      
                    ]),
                    new GreaterThan([
                        'value' => 0,
                       
                    ]),
                ],
            ])
            ->add('profit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BilanFinancier::class,
        ]);
    }
}

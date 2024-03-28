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
            ->add('dateDebut')
            ->add('dateFin')
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

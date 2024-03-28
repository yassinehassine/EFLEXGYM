<?php

namespace App\Form;

use App\Entity\BilanFinancier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilanFinancierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut')
            ->add('dateFin')
            ->add('salairesCoachs')
            ->add('prixLocation')
            ->add('revenusAbonnements')
            ->add('revenusProduits')
            ->add('depenses')
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

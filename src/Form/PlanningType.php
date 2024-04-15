<?php

namespace App\Form;

use App\Entity\Planning;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('salle')
            ->add('nbPlaceMax')
            ->add('date')
            ->add('heure')
            ->add('cour', EntityType::class, [
                'class' => 'App\Entity\Cours',
                'choice_label' => 'nom', // Assuming 'nom' is the property you want to display for Cours
            ])
            ->add('coach', EntityType::class, [
                'class' => User::class,
                'label' => 'Coach',
                'choice_label' => 'nom', // Display the coach's name as the choice label
                'query_builder' => function (EntityRepository $er) {
                    // Query only users with the role "Coach"
                    return $er->createQueryBuilder('u')
                        ->where('u.role = :role')
                        ->setParameter('role', 'Coach');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Import ChoiceType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice; // Import Choice constraint

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom du cours ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [ // Use ChoiceType for dropdown selection
                'choices' => [
                    'Presentiel' => 'Presentiel',
                    'En Ligne' => 'En Ligne',
                ],
                'constraints' => [
                    new Choice([ // Use Choice constraint to ensure the value is one of the choices
                        'choices' => ['Presentiel', 'En Ligne'],
                        'message' => 'Le type du cours doit être soit "Presentiel" soit "En Ligne".',
                    ]),
                ],
            ])
            ->add('duree', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La durée du cours ne peut pas être vide.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}

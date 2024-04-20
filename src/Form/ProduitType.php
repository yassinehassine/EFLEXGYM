<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name for the product.'
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => true,
            ])
            ->add('prix', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a price for the product.'
                    ]),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'Please enter a valid price for the product.'
                    ]),
                ],
            ])
            ->add('quantite', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a quantity for the product.'
                    ]),
                    new Type([
                        'type' => 'integer',
                        'message' => 'Please enter a valid quantity for the product.'
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description for the product.'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Description must be at least {{ limit }} characters long.'
                    ]),
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'type',
                'placeholder' => 'Select a category',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a category for the product.'
                    ]),
                ],
            ])
            ->add('id_bilan_financier')
            ->add('id_admin');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            // Enable client-side validation
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'produit'; // Form name prefix
    }

    // Add a method to generate JavaScript code for client-side validation
    public function generateJavaScriptValidation(): string
    {
        $validationCode = <<<JS
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('produit').addEventListener('submit', function (event) {
            var nom = document.getElementById('produit_nom');
            var prix = document.getElementById('produit_prix');
            var quantite = document.getElementById('produit_quantite');
            var description = document.getElementById('produit_description');
            var categorie = document.getElementById('produit_categorie');
    
            clearErrorMessages();
    
            // Validate nom
            if (nom.value.trim() === '') {
                appendErrorMessage(nom, 'Please enter a name for the product.');
            }
    
            // Validate prix
            if (isNaN(parseFloat(prix.value)) || parseFloat(prix.value) <= 0) {
                appendErrorMessage(prix, 'Please enter a valid positive price for the product.');
            }
    
            // Validate quantite
            if (isNaN(parseInt(quantite.value)) || parseInt(quantite.value) <= 0) {
                appendErrorMessage(quantite, 'Please enter a valid positive quantity for the product.');
            }
    
            // Validate description
            if (description.value.trim().length < 10) {
                appendErrorMessage(description, 'Please enter a description with at least 10 characters.');
            }
    
            // Validate categorie
            if (categorie.value === '') {
                appendErrorMessage(categorie, 'Please select a category for the product.');
            }
    
            // Check if there are any errors
            if (document.querySelectorAll('.error-message').length > 0) {
                event.preventDefault(); // Prevent form submission
            }
        });
    });
    
    function clearErrorMessages() {
        var errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(function(errorMessage) {
            errorMessage.remove();
        });
    }
    
    function appendErrorMessage(element, message) {
        var errorMessage = document.createElement('div');
        errorMessage.classList.add('error-message');
        errorMessage.textContent = message;
        element.parentNode.appendChild(errorMessage);
    }
    JS;
    
        return $validationCode;
    }
    
    
}    
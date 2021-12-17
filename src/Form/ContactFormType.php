<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => 'Nom :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir un Nom'
                        ])
                    ]
            ])
            ->add('prenom', TextType::class,[
                'label' => 'Prenom :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir un Prenom'
                        ]),
                    ]
            ])
            ->add('email', TextType::class,[
                'label' => 'Email :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir un Email'
                        ]),
                    ]
            ])
            ->add('commentaire', TextareaType::class,[
                'label' => 'Votre Message :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir votre message :'
                        ])
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

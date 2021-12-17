<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'require' => false,
                'contraints' => [
                    'message' => [
                        new NotBlank([
                            'message' => 'Nom :'
                        ])
                    ]
                ]
            ])
            ->add('prenom', TextType::class,[
                'require' => false,
                'contraints' => [
                    'message' => [
                        new NotBlank([
                            'message' => 'Prenom :'
                        ])
                    ]
                ]
            ])
            ->add('email', TextType::class,[
                'require' => false,
                'contraints' => [
                    'message' => [
                        new NotBlank([
                            'message' => 'Email :'
                        ])
                    ]
                ]
            ])
            ->add('description', TextArea::class,[
                'require' => false,
                'contraints' => [
                    'message' => [
                        new NotBlank([
                            'message' => 'Votre message :'
                        ])
                    ]
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

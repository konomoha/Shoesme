<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photo', FileType::class, [
            'label' => "Ajouter une photo",
            'mapped' => true, 
            'required' => false,
            'data_class' => null,
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/gif',
                        'image/webp'
                    ],
                    'mimeTypesMessage' => 'Formats autorisés : jpg/jpeg/png/gif et taille maximum 5Mo'
                ])
            ]
        ])
        ->add('photo2', FileType::class, [
            'label' => "Photo2",
            'mapped' => true, 
            'required' => false,
            'data_class' => null,
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/gif',
                        'image/webp'
                    ],
                    'mimeTypesMessage' => 'Formats autorisés : jpg/jpeg/png/gif et taille maximum 5Mo'
                ])
            ]
        ])
        ->add('photo3', FileType::class, [
            'label' => "Photo 3",
            'mapped' => true, 
            'required' => false,
            'data_class' => null,
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/gif',
                        'image/webp'
                    ],
                    'mimeTypesMessage' => 'Formats autorisés : jpg/jpeg/png/gif et taille maximum 5Mo'
                ])
            ]
        ])   
        ->add('photo4', FileType::class, [
            'label' => "Photo 4",
            'mapped' => true, 
            'required' => false,
            'data_class' => null,
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/gif',
                        'image/webp'
                    ],
                    'mimeTypesMessage' => 'Formats autorisés : jpg/jpeg/png/gif et taille maximum 5Mo'
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

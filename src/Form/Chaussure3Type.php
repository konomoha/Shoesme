<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Chaussure3Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class,[
            'label' => 'Catégorie :',
            'choices' => [
                'sneakers' => 'sneakers',
                'montantes' => 'montantes',
                'derby' => 'derby',
                'bottines' => 'bottines',
                'bottes' => 'bottes',
                'escarpin'=>'escarpin',
                'chaussure de marche' => 'chaussure de marche',
                'chausson' => 'chausson'
            ],
            'required' => false,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir une catégorie'
                    ])
                ]
        ])

        ->add('marque', TextType::class,[
            'label' => 'Marque :',
            'required' => false,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir une marque'
                    ])
                ]
        ])  

        ->add('model', TextType::class,[
            'label' => 'Modèle :',
            'required' => false,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir un modèle'
                    ])
                ]
        ])

        ->add('sexe', ChoiceType::class, [
            'choices' => [
                'Homme' => 'm',
                'Femme' => 'f',
                'Garçon' => 'g',
                'Fille' => 'fille',
                'Mixte' => 'Mixte'                   
            ],
            'expanded' => true,
            'multiple' => false,
            'label' => 'Genre',
            'constraints' => [
                new NotBlank([
                    'message' => 'Saisir un genre'
                ])
            ] 
        ])

        ->add('affichage', ChoiceType::class,[
            'label' => 'Affichage :',
            'choices' => [
                'standard' => 'standard',
                'promotion' => 'promotion',
                'nouveauté' => 'nouveaute',
                'destockage' => 'destockage',
                'solde'=> 'solde',
            ],
            'required' => false,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir un affichage'
                    ])
                ]
        ])

        ->add('matiere', TextType::class,[
            'label' => 'matière :',
            'required' => false,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir la matière'
                    ])
                ]
        ])

        ->add('descriptif', TextAreaType::class,[
            'label' => 'Descriptif :',
            'required' => false,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir un Descriptif'
                    ])
                ]
        ])
        
        ->add('couleur', ChoiceType::class,[
            'label' => 'Couleur :',
            'choices' => [
                'blanc' => 'blanc',
                'noir' => 'noir',
                'bleu' => 'bleu',
                'rouge' => 'rouge',
                'gris'=> 'gris',
                'rose'=> 'rose',
                'vert'=> 'vert',
                'jaune'=> 'jaune',
                'beige'=> 'beige',
                'marron'=> 'marron'

            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir une Couleur'
                    ])
                ]
        ])

        ->add('prix', NumberType::class,[
            'label' => 'Prix :',
            'required' => false,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir le prix'
                    ])
                ]
        ])
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
        ->add('pointure',ChoiceType::class,[
            'label' => 'Pointure :',
            'choices' => [
                        '16'=>16,
                        '16.5'=>16.5,
                        '17'=>17,
                        '17.5'=>17.5,
                        '18'=>18,
                        '18.5'=>18.5,
                        '19'=>19,
                        '19.5'=>19.5,
                        '20'=>20,
                        '20.5'=>20.5,
                        '21'=>21,
                        '21.5'=>21.5,
                        '22'=>22,
                        '22.5'=>22.5,
                        '23'=>23,
                        '23.5'=>23.5,
                        '24'=>24,
                        '24.5'=>24.5,
                        '25'=>25,
                        '25.5'=>25.5,
                        '26'=>26,
                        '26.5'=>26.5,
                        '27'=>27,
                        '27.5'=>27.5,
                        '28'=>28,
                        '28.5'=>28.5,
                        '29'=>29,
                        '29.5'=>29.5,
                        '30'=>30,
                        '30.5'=>30.5,
                        '31'=>31,
                        '31.5'=>31.5,
                        '32'=>32,
                        '32.5'=>32.5,
                        '33'=>33,
                        '33.5'=>33.5,
                        '34'=>34,
                        '34.5'=>34.5,
                        '35'=>35,
                        '35.5'=>35.5,
                        '36'=>36,
                        '36.5'=>36.5,
                        '37'=>37,
                        '37.5'=>37.5,
                        '38'=>38,
                        '38.5'=>38.5,
                        '39'=>39,
                        '39.5'=>39.5,
                        '40'=>40, 
                        '40.5'=>40.5,
                        '41'=>41,
                        '41.5'=>41.5,
                        '42'=>42,
                        '42.5'=>42.5,
                        '43'=>43,
                        '43.5'=>43.5,
                        '44'=>44,
                        '44.5'=>44.5,
                        '45'=>45,
                        '45.5'=>45.5,
                        '46'=>46,
                        '46.5'=>46.5,
                        '47'=>47,
                ],
            
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir une pointure'
                    ])
                ]
        ])
        ->add('stock', NumberType::class,[
            'label' => 'Stock par pointure:',
            'required' => false,
            
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

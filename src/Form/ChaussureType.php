<?php

namespace App\Form;


use App\Entity\Chaussure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChaussureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

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
                'label' => 'Genre' 
            ])
            ->add('marque', TextType::class,[
                'label' => 'Marque :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
                        ])
                    ]
            ])  
            ->add('model', TextType::class,[
                'label' => 'Modèle :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
                        ])
                    ]
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'Catégorie :',
                'choices' => [
                    'sneakers' => 'sneakers',
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
                            'message' => 'Saisir une Marque'
                        ])
                    ]
            ])
            
            ->add('matiere', TextType::class,[
                'label' => 'Matière :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
                        ])
                    ]
            ])
            ->add('descriptif', TextAreaType::class,[
                'label' => 'Descriptif :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
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
            ->add('prix', NumberType::class,[
                'label' => 'Prix :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
                        ])
                    ]
            ])
            ->add('pointure', ChoiceType::class,[
                'label' => 'Pointure :',
                'required' => false,
                'choices' => [
                    '25'=>'25',
                    '26'=>'26',
                    '27'=>'27',
                    '28'=>'28',
                    '29'=>'29',
                    '30'=>'30',
                    '31'=>'31',
                    '32'=>'32',
                    '33'=>'33',
                    '34'=>'34',
                    '35'=>'35',
                    '36'=>'36',
                    '37'=>'37',
                    '38'=>'38',
                    '39'=>'39',
                    '40'=>'40',
                    '41'=>'41',
                    '42'=>'42',
                    '43'=>'43',
                    '44'=>'44',
                    '45'=>'45',
                    '46'=>'46',
                ],
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
                        ])
                    ]
            ])
            ->add('couleur', ChoiceType::class,[
                'choices'=>[
                   'noir'=>'noir',
                   'blanc'=>'blanc',
                   'bleu'=>'bleu',
                   'rouge'=>'rouge',
                   'vert'=>'vert',
                   'rose'=>'rose',
                   'turquoise'=>'turquoise'],
                'label'=> 'Couleur :',
                'required'=>false,
            ])
            ->add('stock', NumberType::class,[
                'label' => 'Stock :',
                'required' => false,
                
            ])
            ;
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chaussure::class,
        ]);
    }
}

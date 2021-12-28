<?php

namespace App\Form;

use App\Entity\Chaussure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Chaussure2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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

            ->add('affichage', TextType::class,[
                'label' => 'Modèle :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
                        ])
                    ]
            ])

            ->add('matiere', TextType::class,[
                'label' => 'Modèle :',
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

            // ->add('photo', FileType::class, [
            //     'label' => "Ajouter une photo",
            //     'mapped' => true, 
            //     'required' => false,
            //     'data_class' => null,
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '5M',
            //             'mimeTypes' => [
            //                 'image/jpeg',
            //                 'image/png',
            //                 'image/jpg',
            //                 'image/gif',
            //                 'image/webp'
            //             ],
            //             'mimeTypesMessage' => 'Formats autorisés : jpg/jpeg/png/gif et taille maximum 5Mo'
            //         ])
            //     ]
            // ])

            ->add('prix', NumberType::class,[
                'label' => 'Prix :',
                'required' => false,
                'constraints' => [
                        new NotBlank([
                            'message' => 'Saisir une Marque'
                        ])
                    ]
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

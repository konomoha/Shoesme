<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            
            ->add('nom', TextType::class,[
                'required'=> false,
                'constraints' => [
                    new NotBlank([
                        'message'=>"Veuillez renseigner votre nom."
                    ])
                ]
            ])

            ->add('prenom', TextType::class,[
                'required'=> false,
                'constraints' => [
                    new NotBlank([
                        'message'=>"Veuillez renseigner votre prenom."
                    ])
                ]
            ])

            ->add('adresse', TextType::class,[
                'required'=> false,
                'constraints' => [
                    new NotBlank([
                        'message'=>"Veuillez renseigner votre adresse."
                    ])
                ]
            ])

            ->add('telephone', NumberType::class,[
                'required'=>false,
                'constraints'=>[
                    new Length([
                        'min' => 10, 
                        'max' => 10,
                        'minMessage' => "Veuillez entrer un numéro de téléphone valide",
                        'maxMessage'=> "Veuillez entrer un numéro de téléphone valide"
                    ]),
                ]
            ])

            ->add('codePostal', NumberType::class,[
                'required'=>false,
                'constraints'=>[
                    new Length([
                        'min' => 5, 
                        'max' => 5,
                        'minMessage' => "Veuillez entrer un code postal à 5 chiffres",
                        'maxMessage'=> "Veuillez entrer un code postal à 5 chiffres"
                    ]),
                ]
            ])
            ->add('ville', TextType::class,[
                'required'=> false,
                'constraints' => [
                    new NotBlank([
                        'message'=>"Veuillez renseigner votre ville."
                    ])
                ]
            ])

            ->add('dateNaissance', DateType::class, [
                
                'widget' => 'single_text',
                "format" => 'yyyy-MM-dd',
                
            ])

            ->add('avatar', FileType::class, [
                'label' => "Uploader une photo",
                'mapped' => true, 
                'data_class'=> null,
                'required'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Formats autorisés : jpg/jpeg/png'
                    ])
                ]
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'm',
                    'Femme' => 'f'                    
                ],
                // 'attr' => [
                //     'style' => 'margin-left: 10px;'
                // ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Civilité' 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

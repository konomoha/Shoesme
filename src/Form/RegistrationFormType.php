<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
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
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            //POUR L'EMAIL, IL FAUDRA AJOUTER UNE CONTRAINTE QUI CONTRÔLE LE RESPECT DE LA CASSE!
            ->add('email', TextType::class,[
                'required'=> false,
                'constraints' => [
                    new NotBlank([
                        'message'=>"Veuillez renseigner votre email."
                    ])
                ]
            ])

            #################### CHECKBOX CONDITIONS GENERALES (fonctionnel, à ajouter si besoin)#############################################

            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])


            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message' =>"Les mots de passe ne correspondent pas",
                'options' =>[
                    'attr' =>[
                        'class' => 'password-field'
                ]
                ],
                'first_options' => [
                    'label' => "Mot de passe"
                ],
                'second_options' =>[
                    'label'=>"Confirmer votre mot de passe"
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=>"Veuillez renseigner votre mot de passe."
                    ]),
                    new Length([
                        'min'=>8,
                        'minMessage' =>"Votre mot de passe doit contenir au minimum 8 caractères."
                    ])

                ]
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

            ->add('telephone', TextType::class,[
                'required'=>false,
                'constraints'=>[
                    //Regex qui prend en compte le '0' initial des numéros de tel
                    new Regex ([
                        'pattern'=>'/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/',
                        'match'=>true,
                        'message'=> "Veuillez entrer un numéro de téléphone valide"
                ])
                
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

// ^
//     (?:(?:\+|00)33|0)     # Dialing code
//     \s*[1-9]              # First number (from 1 to 9)
//     (?:[\s.-]*\d{2}){4}   # End of the phone number
// $

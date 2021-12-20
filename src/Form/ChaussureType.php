<?php

namespace App\Form;

use App\Entity\Couleur;
use App\Entity\Chaussure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChaussureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('type', TextType::class,[
                'label' => 'Type :',
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
                            'image/gif'
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
            // ->add('couleur', ChoiceType::class,[
            //     'choices'=>[
            //        new Couleur ('nomCouleur')],
            //        'nomCouleur->getId()' => 'nomCouleur->getNomCouleur()',
            //     'label'=> 'Couleur :',
            //     'required'=>false,
            // ])
            ;
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chaussure::class,
        ]);
    }
}

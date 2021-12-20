<?php

namespace App\Form;

use App\Entity\Commentaire;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class,[
                'label' => 'Saisir votre commentaire',
                'attr'=> [
                    'placeholder' => "Saisir le contenu de l'article",
                    'row' => 10
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre commentaire'
                    ])
                ]
            ])
            ->add('evaluation', ChoiceType::class, [
                'choices' => [
                    'bof' => '1',
                    'Bien' => '2',
                    'très bien' => '3',
                    'Super'=> '4'
                    
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Note' 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FormEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [ "constraints" => 
            [ new Length([ "max" => 50,
                           "maxMessage" => "Le nom ne peut pas dépasser 50 caractères"
                         ]) 
            ] 
        ])
            ->add('auteur', TextType::class, [ "constraints" => 
            [ new Length([ "max" => 20,
                           "maxMessage" => "Le nom de l'auteur ne peut pas dépasser 20 caractères"
                         ]) 
            ] 
         ])


         ->add('couverture', FileType::class, [ "mapped" => false, "required" => false,
         "constraints" => [ new File([ "mimeTypes" => [ "image/gif", "image/jpeg", "image/png" ],
                                         "mimeTypesMessage" => "Les formats autorisés sont gig, jpeg, png",
                                         "maxSize" => "2048k",
                                         "maxSizeMessage" => "Le fichier ne peut pas faire plus de 2Mo"
                                     ])]
     ])



            ->add('description')
            ->add('lieu')

            ->add('Ajouter event', SubmitType::class, [ 'attr' => [
                'class' => 'btn btn-primary'
              ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

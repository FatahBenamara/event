<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Abonné' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    
                ],
                "required"  => true,
                "multiple" => true,
                "label" => "Rôles"

            ])
            
            ->add('password', PasswordType::class, [
                "mapped" => false,
                "constraints" => [
                    new Length([
                        "min" => 6,
                        "minMessage" => "Le mot de passe doit contenir au moins {{ limit }} caractères"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}

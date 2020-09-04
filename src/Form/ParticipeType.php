<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Event;
use App\Entity\Participe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ParticipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('event', EntityType::class, [
            "class" => Event::class, 
            "choice_label" => "nom"
            ])
            
            ->add('abonne', EntityType::class, [
                "class" => Abonne::class, 
                "choice_label" => "prenom"
            ])
                
                
            ->add('date_at', DateType::class, [
                    "widget" => "single_text"
            ])
        
            ->add('date_fin', DateType::class, [
                    "widget" => "single_text",
                    "required" => false
            ])

            ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participe::class,
        ]);
    }
}

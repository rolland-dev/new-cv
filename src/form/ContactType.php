<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
            'attr'=>[
                'placeholder'=>'@mail',]
            ])
            ->add('nom',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Nom',]
                ])
            ->add('prenom',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Prénom',]
                ])
            ->add('demande', TextareaType::class,[
                'attr'=>[
                    'placeholder'=>'Votre demande',]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class ,[
                'attr'=>[
                    'placeholder'=> '@mail',
                ]
            ])
            ->add('username', TextType::class ,[
                'attr'=>[
                    'placeholder'=> 'Nom utilisateur',
                ]
            ])
            ->add('password', PasswordType::class ,[
                'attr'=>[
                    'placeholder'=> 'Mot de passe',
                ]
            ])
            ->add('confirm_password',PasswordType::class ,[
                'attr'=>[
                    'placeholder'=> 'Confirmez mot de passe',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

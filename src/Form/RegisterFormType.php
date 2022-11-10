<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Username', TextType::class, [
                'label' => 'Username',
                'attr' => [
                    'placeholder' => 'Enter Username',
                    'required' => true,
                ],
            ])
            ->add('Password', PasswordType::class, [
                'label' => 'Username',
                'attr' => [
                    'placeholder' => 'Password',
                    'required' => true,
                    'type' => 'password',
                ],
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

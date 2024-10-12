<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'required' => false, // Allow the user to leave the email field empty
            'attr' => ['class' => 'form-control'],
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false, // Not linked to the User entity directly
            'required' => false, // Password is optional
            'constraints' => [
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    'max' => 4096,
                ]),
            ],
            'invalid_message' => 'The password fields must match.',
            'first_options'  => ['label' => 'Password', 'attr' => ['class' => 'form-control']],
            'second_options' => ['label' => 'Repeat Password', 'attr' => ['class' => 'form-control']],
        ]);
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

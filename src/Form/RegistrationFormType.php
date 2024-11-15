<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;


class RegistrationFormType extends AbstractType
{       
     use RegexTrait;
     private const STRONG_PASSWORD = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#?!@$%^&*\-_]).{8,}$/';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {



        $builder
        ->add('email', null, [
            'label' => 'Email *',
            'attr' => ['class' => 'general__input'],
        ])
        
        ->add('agreeTerms', CheckboxType::class, [
            'constraints' => [
                    new NotBlank([
                        'message' => 'L\'adresse e-mail est obligatoire.',
                    ]),
                    new Email([
                        'message' => 'Veuillez entrer une adresse e-mail valide.',
                    ]),
                ],
            'mapped' => false,
            'label' => '<a href="/privacy/policy">Terms and Conditions</a> *',
            'label_html' => true,
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
        ])
        
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                // new Length([
                //     'min' => 8,
                //     'minMessage' => 'Your password should be at least {{ limit }} characters',
                //     'max' => 4096,
                // ]),
                new Regex(
                    self::STRONG_PASSWORD,
                    message: 'Le mot de passe doit contenir au minimum huit caractères, avec au moins une lettre majuscule, une lettre minuscule, un chiffre, et un caractère spécial (#?!@$ %^&*-_).'
                )
            ],
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'general__input']],
            'required' => true,
            'first_options'  => ['label' => 'Password *'],
            'second_options' => ['label' => 'Repeat Password *'],
        ])
        // ->add('captcha', Recaptcha3Type::class, [
        //     'constraints' => new Recaptcha3(),
        //     'action_name' => 'Registration',
        //     'locale' => 'de',
        // ])
        ;
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

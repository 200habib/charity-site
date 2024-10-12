<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => false,
                'attr' => [
                    'class' => 'general__input',  // Sostituisci con il nome della classe che desideri
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
                'attr' => [
                    'class' => 'general__input',  // Sostituisci con il nome della classe che desideri
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => false,
                'attr' => [
                    'class' => 'general__input',  // Sostituisci con il nome della classe che desideri
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => false,
                'attr' => [
                    'class' => 'general__input',  // Sostituisci con il nome della classe che desideri
                ],
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Postal Code',
                'required' => false,
                'attr' => [
                    'class' => 'general__input',  // Sostituisci con il nome della classe che desideri
                ],
            ])
            ->add('phoneNumber', IntegerType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => [
                    'class' => 'general__input',  // Sostituisci con il nome della classe che desideri
                ],
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use App\Entity\UserProfile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Aggiunto per i ruoli
use Symfony\Component\OptionsResolver\OptionsResolver;

class User2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Vendeur' => 'ROLE_SELLER',
                    'Client' => 'ROLE_USER',
                    'Association Caritative' => 'ROLE_CHARITY_ASSOCIATION',
                ],
                'expanded' => true,
                'multiple' => true, 
            ])
            
            
            ->add('password', null, [
                'required' => false,
                'empty_data' => '',
            ])
            ->add('userProfile', UserProfileType::class, [
                'label' => 'User Profile',
                'required' => false,
            ])
            ->add('company', CompanyType::class, [
                'label' => 'Company',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

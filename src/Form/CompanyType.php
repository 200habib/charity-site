<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName', TextType::class, [
                'label' => 'Company Name',
                'required' => false,
                'attr' => ['class' => 'general__input'],
            ])
            ->add('numberSiren', IntegerType::class, [
                'label' => 'SIREN Number',
                'required' => false,
                'attr' => ['class' => 'general__input'],
            ])
            ->add('companyAddress', TextType::class, [
                'label' => 'Company Address',
                'required' => false,
                'attr' => ['class' => 'general__input'],
            ])
            ->add('companyCity', TextType::class, [
                'label' => 'Company City',
                'required' => false,
                'attr' => ['class' => 'general__input'],
            ])
            ->add('companyPostalCode', TextType::class, [
                'label' => 'Postal Code',
                'required' => false,
                'attr' => ['class' => 'general__input'],
            ])
            ->add('creditPoints', IntegerType::class, [
                'label' => 'Credit Points',
                'required' => false,
                'attr' => ['class' => 'general__input'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}

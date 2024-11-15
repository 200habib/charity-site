<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sortPrice', ChoiceType::class, [
                'choices' => [
                    'Pertinence' => '',
                    'Prix croissant' => 'asc',
                    'Prix décroissant' => 'desc',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Trier par prix',
                'required' => false,
            ]);

            // ->add('showSellerProducts', CheckboxType::class, [
            //     'label' => 'Afficher mes produits',
            //     'required' => false,
            //     'data' => false, 
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}

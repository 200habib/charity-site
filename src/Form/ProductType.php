<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category; 
use App\Enum\ProductUnit; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'product__name'],
                'required' => true,
            ])
            ->add('description', null, [
                'attr' => ['class' => 'product__description']
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'product__price'],
                'html5' => true 
            ])
            ->add('volumeLitre', NumberType::class, [
                'attr' => ['class' => 'product__volume general__input', 'placeholder' => 'Quantité'],
                'required' => false,
                'label' => false,
                'html5' => true 
            ])
            ->add('weight', NumberType::class, [
                'attr' => ['class' => 'product__weight general__input', 'placeholder' => 'Quantité'],
                'required' => true,
                'label' => false,
                'html5' => true 
            ])
            ->add('unitType', ChoiceType::class, [
                'choices' => [
                    'masse' => 'weight',
                    'Volume' => 'volumeLitre',
                ],
                'mapped' => false,
                'data' => 'weight', 
                'required' => true,
                'label' => false,
            ])
            ->add('imageFile', VichFileType::class, [
                'label' => false,
                'required' => true,
                'allow_delete' => true,
                'download_uri' => true,
                'attr' => ['class' => 'product__imageFile']
            ])
            ->add('category', null, [
                'class' => Category::class,
                'choice_label' => 'name', 
                'placeholder' => 'Sélectionnez une catégorie',
                'required' => true,
            ])
            ->add('unit', ChoiceType::class, [
                'choices' => ProductUnit::cases(),
                'choice_label' => fn($choice) => $choice->value,
                'placeholder' => 'Sélectionnez une unité',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

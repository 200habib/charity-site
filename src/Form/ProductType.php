<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\StockType;
use App\Enum\ProductUnit; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, [
            'attr' => ['class' => 'product__name'],
            'required' => true,
            'label' => 'Nom du produit *'
        ])
        ->add('description', null, [
            'attr' => ['class' => 'product__description'],
            'label' => 'Description *'
        ])
        ->add('price', NumberType::class, [
            'attr' => ['class' => 'product__price'],
            'html5' => true,
            'required' => true,
            'label' => 'Prix *'
        ])
        ->add('volumeLitre', NumberType::class, [
            'attr' => ['class' => 'product__volume general__input', 'placeholder' => 'Quantité'],
            'required' => false,
            'label' => 'Volume en litres'
        ])
        ->add('weight', NumberType::class, [
            'attr' => ['class' => 'product__weight general__input', 'placeholder' => 'Quantité'],
            'required' => true,
            'label' => 'Poids *'
        ])
        ->add('unitType', ChoiceType::class, [
            'choices' => [
                'Masse' => 'weight',
                'Volume' => 'volumeLitre',
            ],
            'mapped' => false,
            'data' => 'weight', 
            'required' => true,
            'label' => 'Type d\'unité *'
        ])
        ->add('imageFile', VichFileType::class, [
            'label' => 'Image du produit *',
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
            'label' => 'Catégorie *'
        ])
        ->add('unit', ChoiceType::class, [
            'choices' => ProductUnit::cases(),
            'choice_label' => fn($choice) => $choice->value,
            'placeholder' => 'Sélectionnez une unité',
            'required' => true,
            'label' => 'Unité *'
        ])
        

            ->add('stock', StockType::class);
            
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

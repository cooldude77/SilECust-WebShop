<?php

namespace App\Form\MasterData\Product\Filter;

use App\Form\MasterData\Product\Filter\DTO\ProductAttributeKeyValueDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterFormSingleSelect extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add("idProductAttributeKey", HiddenType::class);
        $builder->add("idProductAttributeKeyValue", HiddenType::class);
        $builder->add('value', TextType::class);
        $builder->add('isSelected', CheckboxType::class,['required'=>false]);
    }

    public function getBlockPrefix(): string
    {
        return 'product_attribute_key_value_form';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ProductAttributeKeyValueDTO::class);
    }
}
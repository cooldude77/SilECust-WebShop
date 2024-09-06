<?php

namespace App\Form\MasterData\Product\Filter;

use App\Form\MasterData\Product\Filter\DTO\ProductAttributeKeyValueMultipleDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterMultipleForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('attributeKeyValues', CollectionType::class, ['entry_type' => ProductFilterFormSingleSelect::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'product_attribute_collection_key_value_form';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ProductAttributeKeyValueMultipleDTO::class);
    }
}
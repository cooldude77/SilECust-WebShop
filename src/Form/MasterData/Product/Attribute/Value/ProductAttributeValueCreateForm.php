<?php

namespace Silecust\WebShop\Form\MasterData\Product\Attribute\Value;

use Silecust\WebShop\Form\MasterData\Product\Attribute\Value\DTO\ProductAttributeValueDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeValueCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', TextType::class);
        $builder->add('name', TextType::class);
        $builder->add('value', TextType::class);
        $builder->add(
            'productAttributeId', TextType::class
        );

        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductAttributeValueDTO::class]);
    }
    public function getBlockPrefix(): string
    {
        return 'product_attribute_value_create_form';
    }
}
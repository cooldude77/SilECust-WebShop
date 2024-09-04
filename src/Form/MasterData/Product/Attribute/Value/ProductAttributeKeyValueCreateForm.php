<?php

namespace App\Form\MasterData\Product\Attribute\Value;

use App\Form\MasterData\Product\Attribute\Value\DTO\ProductAttributeKeyValueDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeKeyValueCreateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
        $builder->add('name', TextType::class);
        $builder->add('value', TextType::class);
        $builder->add(
            'ProductAttributeKeyId', HiddenType::class
        );

        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductAttributeKeyValueDTO::class]);
    }
    public function getBlockPrefix(): string
    {
        return 'product_attribute_value_create_form';
    }
}
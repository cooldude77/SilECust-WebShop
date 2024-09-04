<?php

namespace App\Form\MasterData\Product\Attribute\Value;

use App\Form\MasterData\Product\Attribute\Value\DTO\ProductAttributeKeyValueDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeKeyValueEditForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('value', TextType::class);

        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductAttributeKeyValueDTO::class]);
    }
    public function getBlockPrefix(): string
    {
        return 'product_attribute_value_edit_form';
    }
}
<?php

namespace Silecust\WebShop\Form\MasterData\Product\Type;

use Silecust\WebShop\Form\MasterData\Product\Type\DTO\ProductTypeDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductTypeCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('value', TextType::class);
        $builder->add('Save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductTypeDTO::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'product_type_create_form';
    }
}
<?php

namespace App\Form\Admin\Product\Type;

use App\Form\Admin\Product\Type\DTO\ProductTypeDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductTypeUpdateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', TextType::class);
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
        return 'product_type_edit_form';
    }
}
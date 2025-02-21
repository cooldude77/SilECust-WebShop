<?php

namespace Silecust\WebShop\Form\MasterData\Inventory;

use Silecust\WebShop\Form\CategoryAutoCompleteField;
use Silecust\WebShop\Form\MasterData\Inventory\DTO\InventoryProductDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventoryProductCreateForm extends AbstractType
{

    // If one uses model transformer then only category id is provided in controller
    // instead, do not use it. You get a category entity object in mapper directly

   function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('productId', HiddenType::class);
        $builder->add('quantity', NumberType::class);

        $builder->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => InventoryProductDTO::class]);
    }


    public function getBlockPrefix(): string
    {
        return 'inventory_product_create_form';
    }
}